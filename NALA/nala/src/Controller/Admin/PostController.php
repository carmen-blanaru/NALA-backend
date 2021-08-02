<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\Form\PostType;
use App\Repository\PostRepository;
use App\Service\PictureUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/admin/post", name="admin_post_")
 * @IsGranted("ROLE_ADMIN")
 */

class PostController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, PostRepository $postRepository): Response
    {

        $dql   = "SELECT p.id, p.picture, p.title, p.display, p.createdAt, p.updatedAt FROM App\Entity\Post p";
        $query = $em->createQuery($dql);

        $pagination = $paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1), /*page number*/
        10 /*limit per page*/
    );
    //dd($pagination);
        // parameters to template
        return $this->render('admin/post/index.html.twig', ['postList' => $pagination]);
       
        /*
        $postList = $postRepository->findAll();
        return $this->render('admin/post/index.html.twig', [
            'controller_name' => 'PostController',
            'postList'        => $postList
        ]);*/
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
     * @Route("/{id}/delete", name="delete", methods={"GET", "POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function deletePost (Post $post, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $submittedToken = $csrfTokenManager->getToken('delete-item')->getValue();
      //  dd($submittedToken);
         if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($post);
            $entityManager->flush();
            return $this->redirectToRoute('admin_post_list');
         }
    
    }    

}
