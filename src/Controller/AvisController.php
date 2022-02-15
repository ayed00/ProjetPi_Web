<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis" , name="avis")
     */
    public function AddavisAction(Request $request){
        $avis = new avis();
        $form = $this->createFormBuilder(AvisType::class, $avis);
        $form->handleRequest($request);
        dump($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('avis_show');
        }
        return $this->render("avis/add_avis.html.twig",array('form'=>$form->createView()));
    }


}

