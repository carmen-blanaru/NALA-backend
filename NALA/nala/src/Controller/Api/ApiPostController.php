<?php

namespace App\Controller\Api;


use App\Entity\Post;
use App\Entity\User;
use App\Entity\Category;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonDecode;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/v1/post", name="api_post_")
 */

class ApiPostController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Return all the posts from the site
     *       
     *  @Route("/", name="list", methods={"GET"})
     */
    public function index(PostRepository $postRepository): Response
    {
        $Post = $postRepository->findAll();
        // dd($Post);
        return $this->json($Post,200,[],[
                'groups' => 'post'
            ]);
    }
    /**
     * Return the last 10 created posts
     *  
     *  @Route("/last10", name="last10", methods={"GET"})
     */
    public function last10(PostRepository $postRepository): Response
    {
        $Post = $postRepository->findLast10();
        // dd($Post);
        return $this->json($Post,200,[],[
                'groups' => 'post'
            ]);
    }
    /**
     * Return a specific post from its ID
     * 
     *  @Route("/{id}", name="show", methods={"GET"})
     */
    public function show($id, PostRepository $postRepository): Response
    {
        $Post = $postRepository->find($id);
        // dd($Post);
        return $this->json($Post,200,[],[
                'groups' => 'post'
            ]);
    }  
    /**
     * Add a new post in the data base
     *
     * @Route("/", name="add", methods={"POST"})
     */
    public function add(Request $request, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        // Retrieve the data sent by the API user
        $dataSentByUser = $request->getContent();

        // Our Json data are transformed in a post object
        $newPost = $serializer->deserialize($dataSentByUser, Post::class, 'json');
        
        $errors = $validator->validate($newPost);
        //dd($newPost);
        if (count($errors)===0 ) {
            $this->em->persist($newPost);
            $this->em->flush();
            
            // Success code the entry has been added to the database
            return $this->json([
                'message' => "La ressource à bien été crée"
            ],201 );
        }
        // Code 400: Bad request 
        return $this->json([
            'errors' => (string) $errors
        ],400 );        
    }
    /**
     * Change a post already stored in the database
     *
     * @Route("/{id}", name="modify", methods={"PUT|PATCH"})
     */
    public function modify($id, PostRepository $postRepository, UserRepository $userRepository, CategoryRepository $categoryRepository, Request $request, ValidatorInterface $validator)
    {
        $Post = $postRepository->find($id);

        // Retrieve the data sent by the API user
        $dataSentByUser = $request->getContent();
  
        // Transform the data sent in an associative table 
        $arrayDataSentByUser = json_decode($dataSentByUser, true);
        
        if (isset($arrayDataSentByUser['picture'])) {
            $Post->setPicture($arrayDataSentByUser['picture']);
        }
        if (isset($arrayDataSentByUser['title'])) {
            $Post->setTitle($arrayDataSentByUser['title']);
        }
        if (isset($arrayDataSentByUser['display'])) {
            $Post->setDisplay($arrayDataSentByUser['display']);
        }
        if (isset($arrayDataSentByUser['user'])) {
            $User = $userRepository->find($arrayDataSentByUser['user']);
            $Post->setUser($User);
        }
        if (isset($arrayDataSentByUser['category'])) {
            $Category = $categoryRepository->find($arrayDataSentByUser['category']);
            $Post->setCategory($Category);
        }
        $errors = $validator->validate($Post);
        //dd($newPost);
        if (count($errors)===0 ) {
            $this->em->persist($Post);
            $this->em->flush();
            
            // Success code the entry has been added to the database
            return $this->json([
                'message' => "La ressource à bien été modifiée"
            ],201 );
        }
        // Code 400: Bad request 
        return $this->json([
            'errors' => (string) $errors
        ],400 );  
    }

    /** 
     * Endpoint to delete a post
     * @Route("/{id}", name="delete", methods={"DELETE"})
     * @param  $post
     * @return void
     */
    public function delete(Post $post)
    {
        $this->em->remove($post);
        $this->em->flush();

        return $this->json('', 204);
    }

}