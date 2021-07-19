<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Form\UserType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


 /**
     * @Route("/admin/utilisateurs", name="admin_user_")
     */
class UserController extends AbstractController
{
    /**
     * @Route("/liste", name="list", methods={"GET"})
     * 
     * this method will list all the users' accounts
     */
    public function index(): Response
    {
        return $this->render('admin/user/index.html.twig');
    }

    /**
     * @Route("/create", name="create", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function newUser(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User;
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //the password is recovered before hashing
           $plainPassword = $form->get('password')->getData();

           // the password is hashed here
           $hashedPassword = $passwordHasher->hashPassword(
               $user,
               $plainPassword
           );
           // the password is updated
           $user->setPassword($hashedPassword);
       
            // the new user account is sent to the database
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            //if all the informations are validated after submission, the adminer is redicted to the users' list
            return $this->redirectToRoute('admin_user_list');
         }  

          // if not, the adminer is still on the user creation account page 
         return $this->render('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);  
    }
}
