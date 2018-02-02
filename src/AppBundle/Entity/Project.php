<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="customer_name", type="string", length=255)
     */
    private $customerName;

    /**
     * @var string
     *
     * @ORM\Column(name="project_name", type="string", length=255)
     */
    private $projectName;

    /**
     * @var string
     *
     * @ORM\Column(name="project_address", type="string", length=255)
     */
    private $projectAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="project_city", type="string", length=255)
     */
    private $projectCity;

    /**
     * @var string
     *
     * @ORM\Column(name="project_state", type="string", length=2)
     */
    private $projectState;

    /**
     * @var int
     *
     * @ORM\Column(name="project_zip", type="integer")
     */
    private $projectZip;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="project_start_date", type="datetime")
     */
    private $projectStartDate;

    /**
     * @var float
     *
     * @ORM\Column(name="project_outstanding_debt", type="float", nullable=true)
     */
    private $projectOutstandingDebt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="project_commenecement_date", type="datetime", nullable=true)
     */
    private $projectCommenecementDate;

    /**
     * @ORM\OneToMany(targetEntity="Orders", mappedBy="project")
     */
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customerName
     *
     * @param string $customerName
     * @return Project
     */
    public function setCustomerName($customerName)
    {
        $this->customerName = $customerName;

        return $this;
    }

    /**
     * Get customerName
     *
     * @return string 
     */
    public function getCustomerName()
    {
        return $this->customerName;
    }

    /**
     * Set projectName
     *
     * @param string $projectName
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string 
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set projectAddress
     *
     * @param string $projectAddress
     * @return Project
     */
    public function setProjectAddress($projectAddress)
    {
        $this->projectAddress = $projectAddress;

        return $this;
    }

    /**
     * Get projectAddress
     *
     * @return string 
     */
    public function getProjectAddress()
    {
        return $this->projectAddress;
    }

    /**
     * Set projectCity
     *
     * @param string $projectCity
     * @return Project
     */
    public function setProjectCity($projectCity)
    {
        $this->projectCity = $projectCity;

        return $this;
    }

    /**
     * Get projectCity
     *
     * @return string 
     */
    public function getProjectCity()
    {
        return $this->projectCity;
    }

    /**
     * Set projectState
     *
     * @param string $projectState
     * @return Project
     */
    public function setProjectState($projectState)
    {
        $this->projectState = $projectState;

        return $this;
    }

    /**
     * Get projectState
     *
     * @return string 
     */
    public function getProjectState()
    {
        return $this->projectState;
    }

    /**
     * Set projectZip
     *
     * @param integer $projectZip
     * @return Project
     */
    public function setProjectZip($projectZip)
    {
        $this->projectZip = $projectZip;

        return $this;
    }

    /**
     * Get projectZip
     *
     * @return integer 
     */
    public function getProjectZip()
    {
        return $this->projectZip;
    }

    /**
     * Set projectStartDate
     *
     * @param \DateTime $projectStartDate
     * @return Project
     */
    public function setProjectStartDate($projectStartDate)
    {
        $this->projectStartDate = $projectStartDate;

        return $this;
    }

    /**
     * Get projectStartDate
     *
     * @return \DateTime 
     */
    public function getProjectStartDate()
    {
        return $this->projectStartDate;
    }

    /**
     * Set projectOutstandingDebt
     *
     * @param float $projectOutstandingDebt
     * @return Project
     */
    public function setProjectOutstandingDebt($projectOutstandingDebt)
    {
        $this->projectOutstandingDebt = $projectOutstandingDebt;

        return $this;
    }

    /**
     * Get projectOutstandingDebt
     *
     * @return float 
     */
    public function getProjectOutstandingDebt()
    {
        return $this->projectOutstandingDebt;
    }

    /**
     * Set projectCommenecementDate
     *
     * @param \DateTime $projectCommenecementDate
     * @return Project
     */
    public function setProjectCommenecementDate($projectCommenecementDate)
    {
        $this->projectCommenecementDate = $projectCommenecementDate;

        return $this;
    }

    /**
     * Get projectCommenecementDate
     *
     * @return \DateTime 
     */
    public function getProjectCommenecementDate()
    {
        return $this->projectCommenecementDate;
    }

    /**
     * @return Collection|Orders[]
     */
    public function getOrders()
    {
        return $this->orders;
    }
}
