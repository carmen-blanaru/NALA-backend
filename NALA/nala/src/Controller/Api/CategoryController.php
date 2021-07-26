<?php

namespace App\Controller\Api;

use App\Entity\Category;
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
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function showCategoryById(Category $category)
    {
        return $this->json($category, 200, [], [
            'groups' => 'showById'
        ]);
    }
    /**
     * @Route("", name="list", methods={"GET"})
     */
    public function categoryList(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->listCategoryLimitedFivePictures();
        //dd($categories);
        return $this->json($categories, 200, [], [
            'groups' => 'category'
        ]);
    }
}
