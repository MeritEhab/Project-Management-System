<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Project;
use AppBundle\Entity\Orders;
use AppBundle\Utils\OrderUtils;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends Controller
{
    /**
     * @Route("/home", name="home")
     */
    public function indexAction(Request $request)
    {
        $dispaly_text = "Hello User";
        return $this->render(
		'project/home.html.twig', 
		array('dispaly_text' => $dispaly_text
        ));
    }

    /**
     * @Route("/display", name="details")
     */
    public function importAction()
    {

        if(isset($_POST['submit']))
        {
             $fname = $_FILES['sel_file']['name'];
             echo '<h3>upload file name: '.$fname.'<br></h3>';
             $chk_ext = explode(".",$fname);
            
             if(strtolower(end($chk_ext)) == "csv")
             {
                $filename = $_FILES['sel_file']['tmp_name'];
                $handle = fopen($filename, "r");
                $i = 1;
                while (($data = fgetcsv($handle,1000, ",")) !== FALSE)
                 {  
                    if($i == 1){ $i++; continue; }
                    if($data[7]===Null){ $data[7]='';}
                    if($data[8]===Null){$data[8]='';}
                    if(!$data[0]|| !$data[1]||!$data[2]||!$data[3]||!$data[4]||!$data[5]||!$data[6])
                    {
                        echo "<br><strong>Row Number ".$i." hasn't been imported as an object as required fileds are missing</strong><br>";
                        $i++;
                        continue;
                    }
                    if(strlen($data[5]) > 5 || strlen($data[5]) < 5 )
                    {
                        echo "<br><strong>Row Number ".$i." hasn't been imported as zip code is not correct </strong><br>";
                        $i++;
                        continue;
                    } 
                    $order_utils = new OrderUtils();
                    $project_obj = $this->createProject($data);
                    $order_obj = $this->createOrders($data, $project_obj, $order_utils);
                    $i++;
                 }
           
                 fclose($handle);
                 echo "<h3><br>File has been successfully Imported</h3>";
                
             }
             else
             {
                 echo "Invalid File";
             }   
        }
        return $this->render('project/display.html.twig');
    }

    public function createProject($imported_data)
    {     
        $enm = $this->getDoctrine()->getEntityManager();
        $query = $enm->createQueryBuilder();
        $query
        ->select('Project.id')
        ->from('AppBundle:Project','Project')
        ->where('Project.projectAddress = :address')
        ->andWhere('Project.projectCity = :city')
        ->andWhere('Project.projectState = :state')
        ->andWhere('Project.projectZip = :zip')
        ->SetParameters(array('address'=> $imported_data[2], 'city'=> $imported_data[3],
         'state' => $imported_data[4], 'zip' => $imported_data[5]));
        $result = $query->getQuery()->getOneOrNullResult();
        if ($result)
        {
            $project = $enm->getRepository('AppBundle:Project')->findOneById($result);
            $project->setCustomerName($imported_data[0]);
            $project->setProjectName($imported_data[1]);
            $project->setProjectStartDate(\DateTime::createFromFormat('Y-m-d', $imported_data[6]));
            if ($imported_data[7] != '')
            {
                $project->setProjectOutstandingDebt($imported_data[7]);
            }
            if($imported_data[8] == '' || !$imported_data[8])
            {
                $project->setProjectCommenecementDate(new\DateTime('now'));

            }
            else
            {
                $project->setProjectCommenecementDate(\DateTime::createFromFormat('Y-m-d', $imported_data[8]));
            }
        } 
        else 
        {
            $project = new Project();         
            $project->setCustomerName($imported_data[0]);
            $project->setProjectName($imported_data[1]);
            $project->setProjectAddress($imported_data[2]);
            $project->setProjectCity($imported_data[3]);
            $project->setProjectState($imported_data[4]);
            $project->setProjectZip($imported_data[5]);
            $project->setProjectStartDate(\DateTime::createFromFormat('Y-m-d', $imported_data[6]));
            if ($imported_data[7] != '')
            {
                $project->setProjectOutstandingDebt($imported_data[7]);
            }
            if($imported_data[8] == '' || !$imported_data[8])
            {
                $project->setProjectCommenecementDate(new\DateTime('now'));

            }
            else
            {
                $project->setProjectCommenecementDate(\DateTime::createFromFormat('Y-m-d', $imported_data[8]));
            }
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($project);
        $em->flush();

        return $project;
    }

    public function createOrders($imported_data, $project_obj, OrderUtils $order_utils)
    {
        $order = new Orders();
        if ($imported_data[9] == 'Notice' || $imported_data[9] == 'Lien')
        {
            if($imported_data[9] == 'Notice' )
            {
                $deadline = $order_utils->calculateNoticeOrederDeadline(
                $project_obj->getProjectCommenecementDate(),
                $project_obj->getProjectCity());
            }   
            
            elseif ($imported_data[9] == 'Lien')
            {
                $deadline = $order_utils->calculateLienOrederDeadline($project_obj->getProjectStartDate());
            }
            

            if(\DateTime::createFromFormat('d-m-Y', $deadline) < new \DateTime())
                {
                    echo "<br><strong>Order " . $imported_data[9]. " for project ".$project_obj->getProjectName()." can not be created as it's deadline has passed</strong><br>";
                }
            else
            {
                $order->setProject($project_obj);
                $order->setType($imported_data[9]);
                $order->setDeadline(\DateTime::createFromFormat('d-m-Y', $deadline));
                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush();
            }

        }
        else
        {
            echo "<br><strong>Order " . $imported_data[9]. " for project ".$project_obj->getProjectName()." can not be created as it's not supported</strong><br>";
        }
        return $order;
    }

    /**
     * @Route("/project_report", name="project_report")
     */
    public function projectReport(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $query = $em->createQueryBuilder();
        $query
        ->select('p.customerName','p.projectName','o.type', 'o.deadline')
        ->from('AppBundle:Project', 'p')
        ->leftJoin('p.orders', 'o');
        $projects = $query->getQuery()->getResult();
        return $this->render(
        'project/project_report.html.twig', 
        array('projects' => $projects
        ));
    }

    /**
     * @Route("/outstandingdebt_report", name="outstandingdebt_report")
     */
    public function outstandingdebtReport(Request $request)
    {
       $em = $this->getDoctrine()->getEntityManager();
        
        //select p.customer_name ,count(p.id), sum(p.project_outstanding_debt) from Project as p group by p.customer_name;
        // $fields = array('p.customerName', 'count(p.id)', 'sum(p.projectOutstandingDebt)');
        $query = $em->createQueryBuilder();
        $query
        ->select(array('p.customerName', 'count(p.id) as numOfProjects', 'sum(p.projectOutstandingDebt) as totalDebt'))
        ->from('AppBundle:Project','p')
        ->groupBy('p.customerName');
        $result1 = $query->getQuery()->getResult();

        //select p.customer_name, count(o.id) from project as p left join orders as o on p.id = o.project_id group by p.customer_name
        $query = $em->createQueryBuilder();
        $query
        ->select(array('p.customerName', 'count(o.id) as numOfOrders'))
        ->from('AppBundle:Project','p')
        ->leftJoin('p.orders','o')
        ->groupBy('p.customerName');
        $result2 = $query->getQuery()->getResult();

        return $this->render(
        'project/outstandingdebt_report.html.twig', 
        array('result1' => $result1,
            'result2' => $result2
        ));

    }

}


