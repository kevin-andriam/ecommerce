<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        ProductRepository $productRepository,
        CategoryRepository $categoryRepository
    ): Response
    {
        return $this->render('home/index.html.twig', [
            'products' => $productRepository->findBy([], ['id' => 'DESC'], 6),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}