<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Form\UserType;
use App\Repository\UserRepository;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;



 /**
     * @Route("/admin/utilisateurs", name="admin_user_", requirements={"id" = "\d+"})
     * @IsGranted("ROLE_ADMIN")
     */
class UserController extends AbstractController
{
    /**
     * @Route("/liste", name="list", methods={"GET"})
     *
     * this method will list all the users' accounts
     */
    public function index(UserRepository $userRepository): Response
    {
        $userList = $userRepository->findAll();
        return $this->render('admin/user/index.html.twig', [
            'users' => $userList
        ]);
    }

    /**
     * @Route("/creation", name="create", methods={"GET", "POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     * 
     * @return Response
     */
    public function newUser(Request $request, UserPasswordHasherInterface $passwordHasher, PictureUploader $pictureUploader)
    {
        $newUser = new User;
        $form = $this->createForm(UserType::class, $newUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //the service allowing the pictures upload
            $newFileName = $pictureUploader->upload($form, 'picture');

            // the path to the database is updated
            $newUser->setPicture($newFileName);

            //the password is recovered before hashing
            $plainPassword = $form->get('password')->getData();

            // the password is hashed here
            $hashedPassword = $passwordHasher->hashPassword(
                $newUser,
                $plainPassword
            );
            // the password is updated
            $newUser->setPassword($hashedPassword);
       
            // the new user account is sent to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($newUser);
            $entityManager->flush();

            //if all the informations are validated after submission, the adminer is redicted to the users' list
            return $this->redirectToRoute('admin_user_list');
        }

        // if not, the adminer is still on the user creation account page
        return $this->render('admin/user/new.html.twig', [
            'user' => $newUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     *
     */
    public function show(User $user)
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/{id}/edition", name="edit", methods={"GET", "POST"})
     *
     */
    public function editUser(User $user, Request $request, PictureUploader $pictureUploader, UserPasswordHasherInterface $passwordHasher)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) {
            $newFileName = $pictureUploader->upload($form, 'picture');

            // the path to the database is updated
            $user->setPicture($newFileName);

            $plainPassword = $form->get('password')->getData();

            if ($plainPassword) {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plainPassword
                );

                $user->setPassword($hashedPassword);
            }
                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $this->addFlash('success', 'Le compte a bien été mise à jour');

                return $this->redirectToRoute('admin_user_show', ['id' => $user->getId()]);
            }  

            return $this->render('admin/user/edit.html.twig', [
                'form' => $form->createView(),
                'user' => $user
        ]);
        
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_user_list');
    }
}
