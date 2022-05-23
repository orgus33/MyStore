<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/mon-panier', name: 'app_cart')]
    public function index(Cart $cart): Response
    {
        $cart_complete = [];

        foreach ($cart->get() as $id => $quantity) {
            $cart_complete[] = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                'quantity' => $quantity
            ];
        }
        return $this->render('cart/index.html.twig', [
            'cart' => $cart_complete
        ]);
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id)
    {

        $cart->add($id);

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/cart/remove', name: 'remove_my_cart')]
    public function remove(Cart $cart)
    {

        $cart->remove();

        return $this->redirectToRoute('app_products');
    }
}
