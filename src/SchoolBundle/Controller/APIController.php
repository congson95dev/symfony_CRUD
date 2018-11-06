<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 05/11/2018
 * Time: 10:45 CH
 */

namespace SchoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use SchoolBundle\Entity\Student;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class APIController extends Controller
{
    /**
     * @Route("/api/", name="api_route")
     */
    public function apiRouteAction()
    {
        return new JsonResponse(['status' => 'OK']);
    }

    /**
     * @Route("/api/students", name="api_students_route")
     */
    public function apiStudentsRouteAction()
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $students = $studentRepo->findAll();

//        $students = null;

        if(empty($students)){
            return new JsonResponse(['message' => 'No student found'], 404);
        } else {
            $students = $this->get('jms_serializer')->serialize($students, 'json');
            return new Response($students);
        }
    }

    /**
     * @Route("/api/student/{id}", name="api_student_route")
     * @Method("GET")
     */
    public function apiStudentRouteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $student = $studentRepo->findById($id);

//        $students = null;

        if(empty($student)){
            return new JsonResponse(['message' => 'No student found'], 404);
        } else {
            $student = $this->get('jms_serializer')->serialize($student, 'json');
            return new Response($student);
        }
    }

    /**
     * @Route("/api/student/add", name="api_add_student_route")
     * @Method("POST")
     */
    public function apiAddStudentRouteAction(Request $req)
    {
        $data = $req->getContent();
        parse_str($data, $data_arr);

        $name = isset($data_arr['name']) ? $data_arr['name'] : '';
        $gender = isset($data_arr['gender']) ? $data_arr['gender'] : '';
        $age = isset($data_arr['age']) ? $data_arr['age'] : '';

        $em = $this->getDoctrine()->getManager();

        $student = new Student();
        $student->setName($name);
        $student->setGender($gender);
        $student->setAge($age);

        $em->persist($student);
        $em->flush();

        if(empty($student)){
            return new JsonResponse(['message' => 'Something went wrong'], 404);
        } else {
            $student = $this->get('jms_serializer')->serialize($student, 'json');
            return new Response($student);
        }
    }

    /**
     * @Route("/api/student/update/{id}", name="api_update_student_route")
     * @Method("PUT")
     */
    public function apiUpdateStudentRouteAction(Request $req, $id)
    {
        $data = $req->getContent();
        parse_str($data, $data_arr);

        $name = isset($data_arr['name']) ? $data_arr['name'] : '';
        $gender = isset($data_arr['gender']) ? $data_arr['gender'] : '';
        $age = isset($data_arr['age']) ? $data_arr['age'] : '';

        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $student = $studentRepo->find($id);

        if(empty($student)){
            return new JsonResponse(['message' => 'No student found with id = ' . $id], 404);
        } else {
            $student->setName($name);
            $student->setGender($gender);
            $student->setAge($age);

            $em->persist($student);
            $em->flush();
            
            $student = $this->get('jms_serializer')->serialize($student, 'json');
            return new Response($student);
        }
    }

    /**
     * @Route("/api/student/delete/{id}", name="api_delete_student_route")
     * @Method("DELETE")
     */
    public function apiDeleteStudentRouteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $studentRepo = $em->getRepository('SchoolBundle:Student');
        $student = $studentRepo->find($id);

        if(empty($student)){
            return new JsonResponse(['message' => 'No student found with id = ' . $id], 404);
        } else {
            $em->remove($student);

            $em->flush();
            
            $student = $this->get('jms_serializer')->serialize($student, 'json');
            return new JsonResponse(['message' => 'Deleted student with id = ' . $id], 200);
        }
    }
}