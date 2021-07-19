<?php

namespace App\Controller\Api;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
