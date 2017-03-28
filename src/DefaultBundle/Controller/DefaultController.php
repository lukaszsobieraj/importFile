<?php

namespace DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use DefaultBundle\Entity\Person;
use Symfony\Component\HttpFoundation\Request;
use DefaultBundle\Services\CurrentDate;

class DefaultController extends Controller {

    public function indexAction($name) {
        return $this->render('DefaultBundle:Default:index.html.twig', array('name' => $name));
    }

    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('DefaultBundle:Person')->findAll();

        $currentDate = $this->get('current_date')->getDate();



        return $this->render('DefaultBundle:Default:list.html.twig', [
                    'person' => $person,
                    'currentDate' => $currentDate
        ]);
    }

    public function editAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('DefaultBundle:Person')->find($id);
        if (!$person) {
            throw $this->createNotFoundException('No object has been found');
        }

        $form = $this->createFormBuilder($person)
                ->add('name', 'text')
                ->add('lastName', 'text')
                ->add('age', 'text')
                ->add('Edit', 'submit')
                ->getForm();

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em->flush();
            $this->addFlash('success', 'Person is updated!');

            return $this->redirectToRoute('list_action');
        }


        $personForm = $form->createView();

        return $this->render('DefaultBundle:Default:edit.html.twig', [
                    'personForm' => $personForm
        ]);
    }

    public function addAction(Request $request) {

        $person = new Person();

        $form = $this->createFormBuilder($person)
                ->add('name', 'text')
                ->add('lastName', 'text')
                ->add('age', 'text')
                ->add('save', 'submit')
                ->getForm();

        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            $this->addFlash('success', 'Person added successfuly');

            return $this->redirectToRoute('list_action');
        }

        $personForm = $form->createView();
        return $this->render('DefaultBundle:Default:create.html.twig', [
                    'personForm' => $personForm
        ]);
    }

    public function deleteAction($id, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('DefaultBundle:Person')->find($id);
        if (!$person) {
            throw $this->createNotFoundException('No object has been found');
        }


        $em->remove($person);
        $em->flush();
        $this->addFlash('success', 'Person is deleted!');

        return $this->redirectToRoute('list_action');
    }

    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $person = $em->getRepository('DefaultBundle:Person')->find($id);

        return $this->render('DefaultBundle:Default:show.html.twig', [
                    'person' => $person
        ]);
    }

    public function emailAction() {

        $name = 'Łukasz';
        $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('send@example.com')
                ->setTo('lukaszsobieraj@aim.com')
                ->setBody(
                $this->render(
                        // app/Resources/views/Emails/registration.html.twig
                        'Emails/email.html.twig', array('name' => $name)
                ), 'text/html'
        );
        $this->get('mailer')->send($message);
     
        
        return new Response('Wysłano wiadomość email');
    }

}
