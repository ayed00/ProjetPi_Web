<?php

namespace App\Controller;

use App\Entity\NotificationReclamation;
use App\Entity\Reclamation;
use App\Form\NotificationReclamationType;
use App\Form\Reclamation1Type;
use App\Repository\NotificationReclamationRepository;
use App\Repository\ReclamationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/backend_reclamation")
 */
class ReclamationController extends AbstractController
{
    /**
     * @Route("/", name="reclamation_index", methods={"GET"})
     */
    public function index(Request $request, PaginatorInterface $paginator,ReclamationRepository $reclamationRepository , NotificationReclamationRepository $notificationReclamationRepository): Response
    {

        // Retrieve the entity manager of Doctrine
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $appointmentsRepository = $em->getRepository(Reclamation::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
            ->where('p.type != :type')
            ->setParameter('type', 'canceled')
            ->getQuery();

        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $allAppointmentsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );

        return $this->render('reclamation/index.html.twig', [
            'pagination' =>  $appointments ,
            'reclamations' => $reclamationRepository->findAll(),
            'notifications' => $notificationReclamationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/afficheReclamation", name="afficheReclamation", methods={"GET"})
     */
    public function afficheReclamation(Request $request, PaginatorInterface $paginator,ReclamationRepository $reclamationRepository): Response
    {
        $em = $this->getDoctrine()->getManager();

        // Get some repository of data, in our case we have an Appointments entity
        $appointmentsRepository = $em->getRepository(Reclamation::class);

        // Find all the data on the Appointments table, filter your query as you need
        $allAppointmentsQuery = $appointmentsRepository->createQueryBuilder('p')
            ->where('p.type != :type')
            ->setParameter('type', 'canceled')
            ->getQuery();

        // Paginate the results of the query
        $appointments = $paginator->paginate(
        // Doctrine Query, not results
            $allAppointmentsQuery,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            5
        );
        return $this->render('reclamation/afficheReclamation.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'pagination' =>  $appointments ,
        ]);
    }

    /**
     * @Route("/new", name="reclamation_new", methods={"GET", "POST"})
     */
    public function new(UserRepository $usrRep ,FlashyNotifier $flashy,Request $request, EntityManagerInterface $entityManager): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);
       $notification= new NotificationReclamation();
        if ($form->isSubmitted() && $form->isValid()) {


            $reclamation->setNotification($notification);
            $user=$this->getUser()->getUsername();
            $currentuser=$usrRep->findOneBy(array('Username'=>$user));
            $reclamation->addUser($currentuser);
            $entityManager->persist($reclamation);
            $entityManager->flush();
            $flashy->success('Event created!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }
        $flashy->error('Error created!', 'http://your-awesome-link.com');
        return $this->render('reclamation/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="reclamation_show", methods={"GET"})
     */
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamation/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }



    /**
     * @Route("/{id}/edit", name="reclamation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Reclamation $reclamation,NotificationReclamation  $notificationReclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}/repondre", name="reclamation_repondre", methods={"GET", "POST"})
     */
    public function repondre(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Reclamation1Type::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('afficheReclamation', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('reclamation/repondre.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form->createView(),
        ]);
    }





    /**
     * @Route("/{id}", name="reclamation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    /**
     * @Route("/front", name="front")
     */
    public function front(): Response
    {
        return $this->render('base_FRONT.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }
}
