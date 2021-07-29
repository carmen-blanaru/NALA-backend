<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Form\PostType;
use App\Repository\PostRepository;
use App\Service\PictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/admin/post", name="admin_post_")
 */

class PostController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(PostRepository $postRepository): Response
    {
        $postList = $postRepository->findAll();
        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController',
            'postList'        => $postList
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     *
     */
    public function show(Post $post)
    {
        return $this->render('admin/post/show.html.twig', [
            'post' => $post
        ]);
    }
    /**
     * @Route("/{id}/edition", name="edit", methods={"GET", "POST"})
     *
     */
    public function editPost (Post $post, Request $request)
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted()&& $form->isValid()) 
        {
   
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Le post a bien été mise à jour');

            return $this->redirectToRoute('admin_post_list') ;
        }
        // if the post has not been submitted open the post edition form 
       return $this->render('admin/post/edit.html.twig', [
        'post' => $post,
        'form' => $form->createView(),
        ]); 
    }

    /**
     * @Route("/{id}/delete", name="delete", methods={"GET","POST"})
     *
     */
    public function deletePost (Post $post, Request $request)
    {
        // if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            return $this->redirectToRoute('admin_post_list');
        // }
 
        
    }    

}
