<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    public function __construct(private CartService $cartService) {}

    // Afficher le panier
    #[Route('/cart', name: 'cart_index')]
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $this->cartService->getFullCart(),
            'total' => $this->cartService->getTotal(),
        ]);
    }

    // Ajouter un produit
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(int $id): Response
    {
        $this->cartService->add($id);
        $this->addFlash('success', 'Produit ajouté au panier !');
        return $this->redirectToRoute('cart_index');
    }

    // Diminuer la quantité
    #[Route('/cart/decrease/{id}', name: 'cart_decrease')]
    public function decrease(int $id): Response
    {
        $this->cartService->decrease($id);
        return $this->redirectToRoute('cart_index');
    }

    // Supprimer un produit
    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(int $id): Response
    {
        $this->cartService->remove($id);
        $this->addFlash('warning', 'Produit retiré du panier.');
        return $this->redirectToRoute('cart_index');
    }

    // Vider le panier
    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(): Response
    {
        $this->cartService->clear();
        $this->addFlash('info', 'Panier vidé.');
        return $this->redirectToRoute('cart_index');
    }
}