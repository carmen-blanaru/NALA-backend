<?php

namespace App\Controller\Api;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/v1/categories", name="api_category_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("", name="list")
     */
    public function categoryList(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        //dd($categories);
        return $this->json($categories, 200, [], [
            'groups' => 'category'
        ]);
    }
}
