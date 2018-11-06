<?php

namespace SchoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SchoolBundle\Entity\Classes;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ClassesController extends Controller
{
	/**
    * @Route("/classes", name="view_all_classes_route")
    */
    public function viewAllClassesRouteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $classesRepo = $em->getRepository('SchoolBundle:Classes');
        $classes = $classesRepo->findAll();

        if(is_null($classes)){
            throw $this->createNotFoundException('No class found');
        } else {
            return $this->render('@School/Classes/classes.html.twig',['classes' => $classes]);
        }
    }

    /**
	* @Route("/addclass", name="add_class_route")
	*/
	public function addClassRouteAction(Request $request)
    {
        $form = $this->createFormBuilder([])
            ->add('name', TextType::class, ['label' => 'Name', 'required' => true, 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'form-control save-btn']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $name = $form->get('name')->getData();

            $class = new classes();
            $class->setName($name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($class);
            $em->flush();

            $this->addFlash('message', 'Added New class '.$name);
            return $this->redirectToRoute('view_all_classes_route');
        }

        return $this->render('@School/Classes/form.html.twig',['form' => $form->createView(), 'page_title' => 'Add']);
    }

    /**
	* @Route("/updateclass/{classId}", name="update_class_route")
	*/
	public function updateClassRouteAction(Request $request, $classId)
    {
        $em = $this->getDoctrine()->getManager();
        $classRepo = $em->getRepository('SchoolBundle:Classes');
        $class = $classRepo->find($classId);

        if(is_null($class)){
            throw $this->createNotFoundException('No class found with id = ' . $classId);
        } else {
            $form = $this->createFormBuilder([])
                ->add('name', TextType::class, ['label' => 'Name', 'required' => true, 'attr' => ['class' => 'form-control', 'value' => $class->getName()]])
                ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'form-control save-btn']])
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() and $form->isValid()){
                $name = $form->get('name')->getData();

                $class->setName($name);

                $em = $this->getDoctrine()->getManager();
                $em->persist($class);
                $em->flush();

                $this->addFlash('message', 'Updated New Class '.$name);
                return $this->redirectToRoute('view_all_classes_route');
            }

            return $this->render('@School/Classes/form.html.twig',['form' => $form->createView(), 'page_title' => 'Update']);
    	}
    }

    /**
	* @Route("/deleteclass/{classId}", name="delete_class_route")
	*/
	public function deleteClassRouteAction($classId)
    {
    	$em = $this->getDoctrine()->getManager();
    	$classRepo = $em->getRepository('SchoolBundle:Classes');
    	$class = $classRepo->find($classId);

    	if(is_null($class)){
    		throw $this->createNotFoundException('No class found with id = ' . $classId);
    	} else {
    		$em->remove($class);

    		$em->flush();

    		// return new Response("class " . $class->getName() . " removed using id: " . $classId);

            $this->addFlash('message', 'Deleted Class With Id = '.$classId);
            return $this->redirectToRoute('view_all_classes_route');
    	}
    }

    /**
    * @Route("/class/{classId}", name="view_class_route")
    */
    public function viewClassRouteAction($classId)
    {
        $em = $this->getDoctrine()->getManager();
        
        $query = $em->createQuery(
            '
                SELECT s.id, s.name, s.gender, s.age
                FROM SchoolBundle\Entity\Classes c
                INNER JOIN SchoolBundle\Entity\StudentInClass sc WITH  c.id = sc.classId
                INNER JOIN SchoolBundle\Entity\Student s WITH  s.id = sc.studentId
                WHERE c.id = :classId
            ' 
        )
        ->setParameter('classId', $classId)
        ;

        $result = $query->execute();

        if(is_null($result)){
            throw $this->createNotFoundException('No class data found with id = ' . $classId);
        } else {
            return $this->render('@School/Students/students.html.twig',['students' => $result]);
        }
    }
}
