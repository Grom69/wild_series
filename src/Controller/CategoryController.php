<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category/index.html.twig', ['categories' => $categories]);
    }

    #[route('/{categoryName}', methods: ['GET'], name: 'show')]
    public function show(string $categoryName, CategoryRepository $categoryRepository, ProgramRepository $programRepository)
    {
        $category = $categoryRepository->findOneBy(
            ['name' => $categoryName]
        );

        if (!$category)
            throw $this->createNotFoundException('No category with name : ' . $categoryName . ' found in categrory\'s table.');

        $programs = $programRepository->findBy(
            ['category' => $category],
            ['id' => 'DESC'],
            3,
            0
        );

        return $this->render('category/show.html.twig', ['programs' => $programs, 'categoryName' => $categoryName]);
    }
}
