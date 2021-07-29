<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/admin/post", name="post")
     */
    public function index(): Response
    {
        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }
}
