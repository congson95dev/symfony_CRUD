<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
	/**
	* @Route("/", name="app_home_route")
	*/
    public function indexAction()
    {
        return $this->render('@Home/Default/index.html.twig');
    }

    /**
	* @Route("/newpage", name="app_newpage_route")
	*/
    public function newPageAction()
    {
        return $this->render('@Home/Pages/newpage.html.twig');
    }

    /**
	* @Route("/testparam/{name}", defaults={"name" = "fudu"}, name="app_testparam_route")
	*/
    public function testParamAction($name)
    {
    	// get params after /
        return $this->render('@Home/Pages/testparam.html.twig',['name' => $name]);
    }
}
