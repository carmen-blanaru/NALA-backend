<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/utilisateurs", name="api_user_", requirements={"id" = "\d+"})
 */
class UserController extends AbstractController
{
    // the variable $em will be used several times, so it is easier to create a contruct function for it 
    private $em; 
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }


    /**
     * @Route("/{id}", name="show", methods={"GET"})
     *
     * this path recovers an user by his id 
     */
    public function showUserAccount(int $id, UserRepository $userRepository): Response
    {
        $user = $userRepository->find($id);
        //dd($user);
        return $this->json($user, 200, [], [
            // the response is in json format so it is importent to serialize the objects in naming groups 
            'groups' => 'user',
        ]);
    }

    /**
     * @Route("", name="add", methods={"POST"})
     *
     * Add a new user account to the database
     */
    public function newUserAccount(Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator)
    {   
        $newUser = new User();
        // data in json format received from insomnia, javascript, etc
        $jsonData = $request->getContent();
        
        // it is necessary to tranform these datas in objects using the deserialize method
        $newUser = $serializer->deserialize($jsonData, User::class, 'json');

        $plainPassword = $newUser->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $newUser,
            $plainPassword
        );
        $newUser->setPassword($hashedPassword);
       
        // important step: verify if the user did errors during the creation of the account
        $errors = $validator->validate($newUser);

        
        // if everything is alright
        if(count($errors) == 0) 
        {   
            // the datas are sent to the database and the process is stopped here with a 201 message
            $this->em->persist($newUser);
            $this->em->flush();

            return $this->json([
                'message' => 'L\'user ' . $newUser->getNickname() . ' a bien été ajouté'
            ], 201);
        }
        // if there are any errors, a 500 message is displayed 
        return $this->json([
            'errors' => (string) $errors
        ], 500);
    }

    /**
     *  @Route("/{id}", name="update", methods={"PUT|PATCH"})
     */
    public function updateUserAccount(User $user, Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator)
    {
         // data in json format received from insomnia, javascript, etc
        $jsonData = $request->getContent();

        // the serializer allows to transform the json format of the datas in objects froms the User class
        // due to the dematerialize method
        $user = $serializer->deserialize($jsonData, User::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $plainPassword = $user->getPassword();

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);
        $errors = $validator->validate($user);
        if(count($errors) == 0)
        {
            $this->em->flush();

            return $this->json([
                'message' => 'Le compte a bien été mis à jour'
            ]);
        }

        return $this->json([
            'errors' => (string) $errors
        ], 400);
        // 400 => BAD request


    }

    /** 
     *@Route("/{id}", name="delete", methods={"DELETE"})
     * @param User $user
     * @return void
     */
    public function deleteUserAccount(User $user)
    {
        $this->em->remove($user);
        $this->em->flush();

        return $this->json('', 204);
    }
}
