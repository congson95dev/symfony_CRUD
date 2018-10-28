<?php

namespace HomeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TutorialController extends Controller
{
	/**
	* @Route("/tutorial/backhome", name="app_tutorial_route")
	*/
    public function tutorialAction()
    {
    	// 2 way to redirect to route, both way works :)

    	// return $this->redirect($this->generateUrl('app_home_route'));

    	// return $this->redirectToRoute('app_home_route');
    }

    /**
	* @Route("/delete/{name}", name="app_delete_tutorial_route")
	*/
    public function deleteTutorialAction(Request $req, $name)
    {
    	// get params after ?
        // delete/fudu?by=new_one
    	$by = $req->query->get('by');

    	return new Response('deleted ' . $name . ' by ' . $by);
    }

    /**
	* @Route("/writeSession", name="app_write_session_route")
	*/
    public function writeSessionAction(Request $req)
    {
    	$this->get('session')->set("shopping_cart",[
    		[
    			'product_name' => 'ring 1',
    			'qty' => '2',
    			'price' => '13000'
    		],
    		[
    			'product_name' => 'ring 2',
    			'qty' => '3',
    			'price' => '15000'
    		]
    	]);
    	
    	return new Response('Cart has been added');
    }

    /**
	* @Route("/readSession", name="app_read_session_route")
	*/
    public function readSessionAction(Request $req)
    {
    	$cart = $this->get('session')->get('shopping_cart');
    	var_dump($cart); exit;
    	return new Response();
    }

    /**
	* @Route("/for", name="app_for_route")
	*/
    public function forAction(Request $req)
    {
    	$data = [
    		[
    			'name' => 'fudu1',
    			'age' => '23',
    			'active' => true
    		],
    		[
    			'name' => 'fudu2',
    			'age' => '23+1',
    			'active' => true
    		],
    		[
    			'name' => 'fudu3',
    			'age' => '23+2',
    			'active' => false
    		]
    	];
    	return $this->render('@Home/Pages/for.html.twig',['data' => $data]);
    }
}
