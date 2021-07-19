<?php

namespace App\Controller\Api;


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
     * Return all the posts from the site
     *      * 
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
     * Return all the posts from the site
     *      * 
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
     * Return a post from the site
     *      * 
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
}
