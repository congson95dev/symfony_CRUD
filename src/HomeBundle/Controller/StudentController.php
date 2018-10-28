<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StudentController extends Controller
{
	/**
	* @Route("/students", name="app_get_students_route")
	* @Method("GET")
	*/
    public function getStudentsAction(Request $req)
    {
    	return $this->render('@Home/Students/index.html.twig');
    }
}
