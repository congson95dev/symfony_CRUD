<?php

namespace SchoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use SchoolBundle\Entity\Student;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('SchoolBundle:Default:index.html.twig');
    }

    /**
    * @Route("/students", name="view_all_students_route")
    */
    public function viewAllStudentsRouteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $students = $studentRepo->findAll();

        if(is_null($students)){
            throw $this->createNotFoundException('No student found');
        } else {
            return $this->render('@School/Students/students.html.twig',['students' => $students]);
        }
    }

    /**
	* @Route("/addstudent", name="add_student_route")
	*/
	public function addStudentRouteAction(Request $request)
    {
        $form = $this->createFormBuilder([])
            ->add('name', TextType::class, ['label' => 'Name', 'required' => true, 'attr' => ['class' => 'form-control']])
            ->add('gender', TextType::class, ['label' => 'Gender', 'required' => true, 'attr' => ['class' => 'form-control']])
            ->add('age', TextType::class, ['label' => 'Age', 'required' => true, 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'form-control save-btn']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() and $form->isValid()){
            $name = $form->get('name')->getData();
            $gender = $form->get('gender')->getData();
            $age = $form->get('age')->getData();

            $student = new Student();
            $student->setName($name);
            $student->setGender($gender);
            $student->setAge($age);

            $em = $this->getDoctrine()->getManager();
            $em->persist($student);
            $em->flush();

            $this->addFlash('message', 'Added New Students '.$name);
            return $this->redirectToRoute('view_all_students_route');
        }

        return $this->render('@School/Students/form.html.twig',['form' => $form->createView(), 'page_title' => 'Add']);
    }

    /**
	* @Route("/updatestudent/{studentId}", name="update_student_route")
	*/
	public function updateStudentRouteAction(Request $request, $studentId)
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $student = $studentRepo->find($studentId);

        if(is_null($student)){
            throw $this->createNotFoundException('No student found with id = ' . $studentId);
        } else {
            $form = $this->createFormBuilder([])
                ->add('name', TextType::class, ['label' => 'Name', 'required' => true, 'attr' => ['class' => 'form-control', 'value' => $student->getName()]])
                ->add('gender', TextType::class, ['label' => 'Gender', 'required' => true, 'attr' => ['class' => 'form-control', 'value' => $student->getGender()]])
                ->add('age', TextType::class, ['label' => 'Age', 'required' => true, 'attr' => ['class' => 'form-control', 'value' => $student->getAge()]])
                ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'form-control save-btn']])
                ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() and $form->isValid()){
                $name = $form->get('name')->getData();
                $gender = $form->get('gender')->getData();
                $age = $form->get('age')->getData();

                $student->setName($name);
                $student->setGender($gender);
                $student->setAge($age);

                $em = $this->getDoctrine()->getManager();
                $em->persist($student);
                $em->flush();

                $this->addFlash('message', 'Updated New Students '.$name);
                return $this->redirectToRoute('view_all_students_route');
            }

            return $this->render('@School/Students/form.html.twig',['form' => $form->createView(), 'page_title' => 'Update']);
    	}
    }

    /**
	* @Route("/deletestudent/{studentId}", name="delete_student_route")
	*/
	public function deleteStudentRouteAction($studentId)
    {
    	$em = $this->getDoctrine()->getManager();
    	$studentRepo = $em->getRepository('SchoolBundle:Student');
    	$student = $studentRepo->find($studentId);

    	if(is_null($student)){
    		throw $this->createNotFoundException('No student found with id = ' . $studentId);
    	} else {
    		$em->remove($student);

    		$em->flush();

    		// return new Response("Student " . $student->getName() . " removed using id: " . $studentId);

            $this->addFlash('message', 'Deleted Students With Id = '.$studentId);
            return $this->redirectToRoute('view_all_students_route');
    	}
    }

     /**
    * @Route("/student/{studentId}", name="view_student_route")
    */
    public function viewStudentRouteAction($studentId)
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $student = $studentRepo->find($studentId);

        if(is_null($student)){
            throw $this->createNotFoundException('No student found with id = ' . $studentId);
        } else {
            return $this->render('@School/Students/view.html.twig',['student' => $student]);
        }
    }

     /**
    * @Route("/students/gender/{gender}", name="view_students_by_gender_route")
    */
    public function viewStudentsByGenderRouteAction($gender)
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');

        // 1st way, using already created function of doctrine
        // $students = $studentRepo->findByGender($gender);

        // 2nd way, create my own function in repository
        $students = $studentRepo->getStudentByGender($gender);
        $count = 0;
        foreach ($students as $data) {
            $count++;
        }

        if(is_null($students)){
            throw $this->createNotFoundException('No student found with render = ' . $gender);
        } else {
            return $this->render('@School/Students/students.html.twig',['students' => $students]);
        }
    }
    
    /**
    * @Route("/addstudentbyform", name="add_student_by_form_route")
    */
    public function addStudentByFormRouteAction(Request $req)
    {
        $em = $this->getDoctrine()->getManager();

        if($req){
            var_dump($req->request->all());
        }
        var_dump('ok');

        // $form = $this->createFormBuilder([])
        //              ->add('name', TextType::class, ['label' => 'Name'])
        // $student = new Student();

        // $student->setName('Fudu1');
        // $student->setGender('Male');
        // $student->setAge('23');

        // $em->persist($student);
        // $em->flush();

        // return new Response("Student " . $student->getName() . " saved using id: " . $student->getId());
        return $this->render('@School/Students/form.html.twig');
    }
}
