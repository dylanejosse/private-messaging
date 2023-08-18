<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="app_message")
     */
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

    /**
     * @Route("/received", name="app_message_received")
     */
    public function receivedMessages(): Response
    {
        return $this->render('message/received.html.twig');
    }

    /**
     * @Route("/sent", name="app_message_sent")
     */
    public function sentMessages(): Response
    {
        return $this->render('message/sent.html.twig');
    }

    /**
     * @Route("/send", name="app_message_send")
     */
    public function send(Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $message->setSender($this->getUser());

            $em->persist($message);
            $em->flush();

            $this->addFlash("message", "Message envoyé avec succès");
            return $this->redirectToRoute("app_message");
        }

        return $this->render('message/send.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
