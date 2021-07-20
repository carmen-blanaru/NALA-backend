<?php

namespace App\Controller\Api;

use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/commentaires", name="api_comment_", requirements={"id" = "\d+"})
 */
class CommentController extends AbstractController
{
    // the variable $em will be used several times, so it is easier to create a contruct function for it 
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;    
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function showComment(Comment $comment): Response
    {
        //dd($comment);
        return $this->json($comment, 200, [], [
            'groups' => 'comment'
        ]);
    }

    /**
     *@Route("", name="add", methods={"POST"})
     *
     * @return void
     */
    public function addNewComment(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        // data in json format received from insomnia, javascript, etc
        $jsonData = $request->getContent();

         // tranformation of these datas in objects using the deserialize method
        $comment = $serializer->deserialize($jsonData, Comment::class, 'json');

        //verify if the user did errors 
        $errors = $validator->validate($comment);
        // if there is one error detected, send an error page 500
        if(count($errors)>0)
        { 
            $errorsString = (string) $errors;
            return $this->json(
                [
                    'error' => $errorsString
                ],
                500
            );
            // if not, send the comment to the database
        }else {
            $this->em->persist($comment);
            $this->em->flush();

            return $this->json([
                'message' => 'Un nouvel commentaire a été ajouté!'
            ], 201);
        }
    }

    /**
     * @Route("/{id}", name="update", methods={"PATCH"})
     */
    public function updateComment(Comment $comment, Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $jsonData = $request->getContent();
        $arrayData = json_decode($jsonData, true);
       // dd($arrayData);
        $commentDescription = $arrayData['description'];

        // if the description is not empty
        if (!empty($commentDescription)) {
            // it is updated in the database
            $comment->setDescription($commentDescription);

            // because it is an update, no need to persist
            $this->em->flush();

            return $this->json([
                'message' => 'Le commentaire a été mis à jour!'
            ]);
        }
        return $this->json([
            'errors' => 'Merci de saisir un commentaire'
        ], 400);
    }

    /**
     * @Route("/{id}", name="delete", methods={"DELETE"})
     */
    public function deleteComment(Comment $comment)
    {   
        $this->em->remove($comment);
        $this->em->flush();

        return $this->json('', 204);

    }
}
