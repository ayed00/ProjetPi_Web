<?php

namespace App\Controller;

use App\Entity\NotificationReclamation;
use App\Form\NotificationReclamation1Type;
use App\Repository\NotificationReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notification/reclamation")
 */
class NotificationReclamationController extends AbstractController
{
    /**
     * @Route("/", name="notification_reclamation_index", methods={"GET"})
     */
    public function index(NotificationReclamationRepository $notificationReclamationRepository): Response
    {
        return $this->render('notification_reclamation/index.html.twig', [
            'notification_reclamations' => $notificationReclamationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="notification_reclamation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $notificationReclamation = new NotificationReclamation();
        $form = $this->createForm(NotificationReclamation1Type::class, $notificationReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($notificationReclamation);
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notification_reclamation/new.html.twig', [
            'notification_reclamation' => $notificationReclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notification_reclamation_show", methods={"GET"})
     */
    public function show(NotificationReclamation $notificationReclamation): Response
    {
        return $this->render('notification_reclamation/show.html.twig', [
            'notification_reclamation' => $notificationReclamation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="notification_reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, NotificationReclamation $notificationReclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationReclamation1Type::class, $notificationReclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('notification_reclamation/edit.html.twig', [
            'notification_reclamation' => $notificationReclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notification_reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, NotificationReclamation $notificationReclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notificationReclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($notificationReclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('notification_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
}
