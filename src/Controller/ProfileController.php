<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfileType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="app_profile")
     */
    public function index(UserRepository $usrRep): Response
    {
        $user=$this->getUser()->getUsername();
        $currentuser=$usrRep->findOneBy(array('Username'=>$user));
        return $this->render('profile/index.html.twig', [
            'user' => $currentuser,
        ]);
    }
    /**
     * @Route("/{id}/editaccount", name="user_editaccount", methods={"GET", "POST"})
     */
    public function editaccount(Request $request,UserPasswordEncoderInterface $userPasswordEncoder, User $user, EntityManagerInterface $entityManager): Response
    {   $usersave=$user;
        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('Photo')->getData();

                if($imageFile!=null) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    $user->setPhoto($newFilename);
                }
                else {
                    $user->setPhoto($usersave->getPhoto());
                }
            $user->setRoles($usersave->getRoles());
            $user->setEmail($usersave->getEmail());
            $user->setPassword(
                $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();

            return $this->redirectToRoute('app_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/editaccount.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }
}
