<?php

namespace App\Controller;


use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/post", name="api_post")
 */

class ApiPostController extends AbstractController
{
    /**
     * Retourne tous les posts du site
     * 
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
}
