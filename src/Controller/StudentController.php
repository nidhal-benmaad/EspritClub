<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    /**
     * @Route("/student", name="student")
     */
    public function index(): Response
    {
        return $this->render('student/index.html.twig', [
            'controller_name' => 'StudentController',
        ]);
    }

    /**
     * @Route("/studentList", name="studentList")
     */
    public function studentList(): Response
    {
        $students = $this->getDoctrine()->getRepository(Student::class)->findAll();
        return $this->render("student/list.html.twig", array('students' => $students));
    }

    /**
     * @Route("/addStudent", name="addStudent")
     */
    public function addStudent(Request $request): Response
    {
        $club = new Student();
        $form = $this->createForm(StudentType::class, $club);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();
            return $this->redirectToRoute('studentList');
        }

        return $this->render("student/add.html.twig", array('studentForm' => $form->createView()));
    }

    /**
     * @Route("/deleteStudent/{id}", name="deleteStudent")
     */
    public function deleteStudent($id): Response
    {
        $student = $this->getDoctrine()->getRepository(Student::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $this->getDoctrine()->getManager()->remove($student);
        $em->flush();
        return $this->redirectToRoute("studentList");
    }
}
