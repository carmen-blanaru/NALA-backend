<?php

namespace App\Controller\Admin;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", name="post")
     */
    public function index(PostRepository $postRepository): Response
    {
        $postList = $postRepository->findAll();
        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController',
            'postList'        => $postList
        ]);
    }
}
