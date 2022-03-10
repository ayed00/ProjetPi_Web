<?php

namespace App\Controller;

use App\Entity\EventsReservation;
use App\Form\EventsReservationType;
use App\Repository\EventsReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twilio\Rest\Client;
/**
 * @Route("/events/reservation")
 */
class EventsReservationController extends AbstractController
{
    /**
     * @Route("/", name="events_reservation_index", methods={"GET"})
     */
    public function index(EventsReservationRepository $eventsReservationRepository): Response
    {
        return $this->render('events_reservation/index.html.twig', [
            'events_reservations' => $eventsReservationRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="events_reservation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $eventsReservation = new EventsReservation();
        $form = $this->createForm(EventsReservationType::class, $eventsReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($eventsReservation);
            $entityManager->flush();
            $sid ='AC43b65f8da7650a3e5124ed658337a625';
            $token = 'f1f9f7d8d9fd225a9b56cda679bcd3e2';
            $client = new Client($sid,$token);
            $to = $eventsReservation->getTel();
            $client->messages->create($to,array('from'=> '+14158401375',
                'body' => 'salem 3andek '.(string)$eventsReservation->getReservations()));
            return $this->redirectToRoute('events_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('events_reservation/new.html.twig', [
            'events_reservation' => $eventsReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="events_reservation_show", methods={"GET"})
     */
    public function show(EventsReservation $eventsReservation): Response
    {
        return $this->render('events_reservation/show.html.twig', [
            'events_reservation' => $eventsReservation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="events_reservation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, EventsReservation $eventsReservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventsReservationType::class, $eventsReservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('events_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('events_reservation/edit.html.twig', [
            'events_reservation' => $eventsReservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="events_reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, EventsReservation $eventsReservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$eventsReservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($eventsReservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('events_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}