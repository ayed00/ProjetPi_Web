<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use App\Repository\UserRepository;
use App\Services\QrcodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Endroid\QrCode\QrCode;
use Endroid\QrCodeBundle\Response\QrCodeResponse;


/**
 * @Route("/avis")
 */
class AvisController extends AbstractController
{
    /**
     * @Route("/", name="avis_index", methods={"GET"})
     */
    public function index(AvisRepository $avisRepository): Response
    {       $qrCode = null;
        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
            'qrCode' => $qrCode
        ]);
    }



    /**
     * @Route("/new", name="avis_new", methods={"GET", "POST"})
     */
    public function new(UserRepository $usrRep ,Request $request, EntityManagerInterface $entityManager): Response
    {
        $qrCode = null;

        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user=$this->getUser()->getUsername();
            $currentuser=$usrRep->findOneBy(array('Username'=>$user));
            $avi->setUser($currentuser);
            $entityManager->persist($avi);
            $entityManager->flush();

            $data= $form->getData();
            //$qrCode= $qrcodeService->qrcode($data['title']);


           return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/new.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
            'qrCode' => $qrCode

        ]);
    }

    /**
     * @Route("/{id}", name="avis_show", methods={"GET"})
     */
    public function show(Avis $avi): Response
    {
        return $this->render('avis/show.html.twig', [
            'avi' => $avi,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="avis_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="avis_delete", methods={"POST"})
     */
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avi->getId(), $request->request->get('_token'))) {
            $entityManager->remove($avi);
            $entityManager->flush();
        }

        return $this->redirectToRoute('avis_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/qrCode/{categ}/{title}", name="Qrcode")
     * @param QrcodeService $qrcodeService
     * @param $categ
     * @param $title
     * @param AvisRepository $avisRepository
     * @return Response
     */
    public function qr( QrcodeService $qrcodeService,$categ,$title,AvisRepository $avisRepository): Response
    {

        $qrCode = $qrcodeService->qrcode($categ."+".$title);

        return $this->render('avis/index.html.twig', [
            'avis' => $avisRepository->findAll(),
            'qrCode' => $qrCode
        ]);
    }

}
