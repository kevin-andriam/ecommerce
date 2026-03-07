<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack,
        private ProductRepository $productRepository
    ) {}

    private function getSession()
    {
        return $this->requestStack->getSession();
    }

    // Ajouter un produit au panier
    public function add(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }
        $this->getSession()->set('cart', $cart);
    }

    // Supprimer un produit du panier
    public function remove(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            unset($cart[$id]);
        }
        $this->getSession()->set('cart', $cart);
    }

    // Diminuer la quantité d'un produit
    public function decrease(int $id): void
    {
        $cart = $this->getSession()->get('cart', []);
        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $this->getSession()->set('cart', $cart);
    }

    // Vider le panier
    public function clear(): void
    {
        $this->getSession()->remove('cart');
    }

    // Obtenir le contenu du panier avec les produits
    public function getFullCart(): array
    {
        $cart = $this->getSession()->get('cart', []);
        $fullCart = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $fullCart[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }
        return $fullCart;
    }

    // Calculer le total du panier
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->getFullCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }
        return $total;
    }

    // Nombre d'articles dans le panier
    public function getCount(): int
    {
        $cart = $this->getSession()->get('cart', []);
        return array_sum($cart);
    }
}