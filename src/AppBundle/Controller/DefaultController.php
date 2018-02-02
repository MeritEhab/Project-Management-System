<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $dispaly_text = "Please follow the instructions sent to you to " 
			. "modify this project and implement the reququirements for zlien's coding exercise.";

        return $this->render(
		'index.html.twig', 
		array('dispaly_text' => $dispaly_text,
        ));
    }
}
