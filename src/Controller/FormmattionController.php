<?php

namespace App\Controller;

use App\Entity\Formmattion;
use App\Form\FormmattionType;
use App\Form\FormmattionSearchType;
use App\Repository\FormmattionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\PaginatorInterface;







/**
 * @Route("/formmattion")
 */
class FormmattionController extends AbstractController
{






    /**
     * @param FormmattionRepository $repository
     * @return Response
     * @Route("/ListDQL", name="ListDQL", methods={"GET"})
     */

    public function orderByPrixDQL(FormmattionRepository $repository)
    {
        $formmattion=$repository->orderByPrix();
        return $this->render('formmattion/affichageformation.html.twig', [
            'formmattion' => $formmattion,
        ]);

    }







    /**
     * @Route("/ajaxsearch ", name="ajaxsearch")
     */
    public function searchOffreajax(Request $request,FormmattionRepository $repository)
    {
        $repository = $this->getDoctrine()->getRepository(Formmattion::class);
        $requestString=$request->get('searchValue');
        $formations= $repository->findeventbyname($requestString);

        return $this->render('formmattion/formationSearch.html.twig', [
            "Formmattion"=>$formations
        ]);
    }
    /**
     * @Route("/Formation/{id}", name="Formation/{id}")
     */
    public function Formationid(Request $request,$id,NormalizerInterface $Normalizer )
    {
        //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
        $em=$this->getDoctrine()->getManager();
        $Formations =$em->getRepository(Formmattion::class)->find($id);

        //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
        //en tableau d'objet Students
        $jsonContent=$Normalizer->normalize( $Formations,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));
    }




    /**
     * @Route("/AddFormm/new", name="AddFormm/new")
     */
    public function AddFormm(Request $request, NormalizerInterface $Normalizer )
    {
        //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données

        //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
        //en tableau d'objet Students
        $em=$this->getDoctrine()->getManager();
        $Formations=new Formmattion();
      //  $Formations->setNom($request->get('nom'));
        $Formations->setNom("qqq");

        $em->persist($Formations);
        $em->flush();
        $jsonContent=$Normalizer->normalize($Formations,'json',['groups'=>'post:read']);

        return new Response(json_encode($jsonContent));




    }
    /**
     * @Route("/AllFormm", name="AllFormm")
     */
    public function AllCategorie(NormalizerInterface $Normalizer )
    {
        //Nous utilisons la Repository pour récupérer les objets que nous avons dans la base de données
        $repository =$this->getDoctrine()->getRepository(Formmattion::class);
        $Formations=$repository->FindAll();
        //Nous utilisons la fonction normalize qui transforme en format JSON nos donnée qui sont
        //en tableau d'objet Students
        $jsonContent=$Normalizer->normalize( $Formations,'json',['groups'=>'post:read']);



        return new Response(json_encode($jsonContent));
        dump($jsonContent);
        die;}






    /**
     * @Route("/listfor", name="formmattion_list", methods={"GET"})
     */
    public function listfor(FormmattionRepository $formmattionRepository): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);


        //l'image est située au niveau du dossier public
        $png = file_get_contents("logo.jpg");
        $pngbase64 = base64_encode($png);



        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('formmattion/listfor.html.twig',[
            'formmattion' => $formmattionRepository->findAll(),
            "img64"=>$pngbase64
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("learn4u-course.pdf", [
            "Attachment" => true
        ]);
    }






    /**
     * @Route("/affichage_formation_front", name="affichage_formation_front", methods={"GET"})
     */
    public function affichage_formation_front(FormmattionRepository $formmattionRepository): Response
    {
        return $this->render('formmattion/affichageformation.html.twig', [
            'formmattion' => $formmattionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/", name="formmattion_index", methods={"GET","POST"})
     */



    public function index(Request $request,FormmattionRepository $formmattionRepository,PaginatorInterface $paginator): Response
    {

        // $Formations = new Formmattion();
        $form = $this->createForm(FormmattionSearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $term = $form['nom']->getData();
            $description = $form['description']->getData();

            $allformations= $formmattionRepository->search($term,$description);

        }
        else
        {
            $allformations= $formmattionRepository->findAll();
        }
        $Formations =$paginator->paginate($allformations,$request->query->getInt('page',1),2);
        return $this->render('formmattion/index.html.twig',[
            'formmattion'=>$Formations,
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/new", name="formmattion_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $usrRep): Response
    {
        $formmattion = new Formmattion();
        $form = $this->createForm(FormmattionType::class, $formmattion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file= $request->files->get('formmattion')['image'];

            $uploads_directory=$this->getParameter('uploads_directory');
            $file_name=md5(uniqid())    . '.'   . $file->guessExtension();
            $file->move(
                $uploads_directory,
                $file_name
            );
            $user=$this->getUser()->getUsername();
            $currentuser=$usrRep->findOneBy(array('Username'=>$user));
            $formmattion->setImage($file_name);
            $formmattion->setUser($currentuser);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($formmattion);
            $entityManager->flush();

            return $this->redirectToRoute('formmattion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formmattion/new.html.twig', [
            'formmattion' => $formmattion,
            'form' => $form->createView(),
        ]);
    }





    /**
     * @Route("/{id}", name="formmattion_show", methods={"GET"})
     */
    public function show(Formmattion $formmattion): Response
    {
        return $this->render('formmattion/show.html.twig', [
            'formmattion' => $formmattion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="formmattion_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Formmattion $formmattion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormmattionType::class, $formmattion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('formmattion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formmattion/edit.html.twig', [
            'formmattion' => $formmattion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="formmattion_delete", methods={"POST"})
     */
    public function delete(Request $request, Formmattion $formmattion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $formmattion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formmattion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('formmattion_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/b", name="affiche_indexb", methods={"GET"})
     */
    public function indexb(Formmattion $formmattion): Response
    {
        return $this->render('formmattion/show.html.twig', [
            'formmattion' => $formmattion,
        ]);
    }













}