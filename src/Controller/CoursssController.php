<?php

namespace App\Controller;

use App\Entity\Coursss;
use App\Form\CoursssType;
use App\Repository\CoursssRepository;
use App\Repository\FormmattionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/coursss")
 */
class CoursssController extends AbstractController
{
    /**
     * @Route("/admin/utilisateur/search", name="utilsearch")
     */
    public function searchPlanajax(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Coursss::class);
        $requestString = $request->get('searchValue');
        $plan = $repository->findPlanBySujet($requestString);
        return $this->render('coursss/utilajax.html.twig', [
            'Coursss' => $plan,
        ]);
    }




    /**
     * @Route("/stat", name="stat_app")
     */
    public function stat(CoursssRepository $repo): Response
    {
        $categorys=$repo->findAll();

        $label=[];
        $count=[];
        foreach($categorys as $category ){
            $label[]=$category->getNom();
            $count[]=count((array)$category->getFormations());

        }
        return $this->render('coursss/stat.html.twig', [
            'label'=>json_encode($label),
            'count'=>json_encode($count),
        ]);
    }






    /**
     * @Route("/affichage_cours_front", name="affichage_cours_front", methods={"GET"})
     */
    public function affichage_cours_front(CoursssRepository $coursssRepository): Response
    {
        return $this->render('coursss/affichecours.html.twig', [
            'courssses' => $coursssRepository->findAll(),
        ]);
    }




    /**
     * @Route("/", name="coursss_index", methods={"GET"})
     */
    public function index(CoursssRepository $coursssRepository): Response
    {
        return $this->render('coursss/index.html.twig', [
            'courssses' => $coursssRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="coursss_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $coursss = new Coursss();
        $form = $this->createForm(CoursssType::class, $coursss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($coursss);
            $entityManager->flush();

            return $this->redirectToRoute('coursss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coursss/new.html.twig', [
            'coursss' => $coursss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coursss_show", methods={"GET"})
     */
    public function show(Coursss $coursss): Response
    {
        return $this->render('coursss/show.html.twig', [
            'coursss' => $coursss,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="coursss_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Coursss $coursss, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CoursssType::class, $coursss);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('coursss_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('coursss/edit.html.twig', [
            'coursss' => $coursss,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="coursss_delete", methods={"POST"})
     */
    public function delete(Request $request, Coursss $coursss, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coursss->getId(), $request->request->get('_token'))) {
            $entityManager->remove($coursss);
            $entityManager->flush();
        }

        return $this->redirectToRoute('coursss_index', [], Response::HTTP_SEE_OTHER);
    }
}
