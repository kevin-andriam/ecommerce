<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CheckoutController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private EntityManagerInterface $em
    ) {}

    // Page de confirmation de commande
    #[Route('/checkout', name: 'checkout')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $items = $this->cartService->getFullCart();

        if (empty($items)) {
            $this->addFlash('warning', 'Votre panier est vide !');
            return $this->redirectToRoute('cart_index');
        }

        return $this->render('checkout/index.html.twig', [
            'items' => $items,
            'total' => $this->cartService->getTotal(),
        ]);
    }

    // Confirmer la commande
    #[Route('/checkout/confirm', name: 'checkout_confirm', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function confirm(): Response
    {
        $items = $this->cartService->getFullCart();

        if (empty($items)) {
            return $this->redirectToRoute('cart_index');
        }

        // Créer la commande
        $order = new Order();
        $order->setUser($this->getUser());
        $order->setStatus('pending');
        $order->setTotal($this->cartService->getTotal());
        $order->setCreatedAt(new \DateTimeImmutable());
        $this->em->persist($order);

        // Créer les lignes de commande
        foreach ($items as $item) {
            $orderItem = new OrderItem();
            $orderItem->setOrderRef($order);
            $orderItem->setProduct($item['product']);
            $orderItem->setQuantity($item['quantity']);
            $orderItem->setPrice($item['product']->getPrice());
            $this->em->persist($orderItem);

            // Réduire le stock
            $product = $item['product'];
            $product->setStock($product->getStock() - $item['quantity']);
            $this->em->persist($product);
        }

        $this->em->flush();

        // Vider le panier
        $this->cartService->clear();

        $this->addFlash('success', 'Commande passée avec succès !');
        return $this->redirectToRoute('checkout_success', ['id' => $order->getId()]);
    }

    // Page de succès
    #[Route('/checkout/success/{id}', name: 'checkout_success')]
    #[IsGranted('ROLE_USER')]
    public function success(int $id): Response
    {
        $order = $this->em->getRepository(Order::class)->find($id);

        return $this->render('checkout/success.html.twig', [
            'order' => $order,
        ]);
    }
}