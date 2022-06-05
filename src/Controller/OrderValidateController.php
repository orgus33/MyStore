<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderValidateController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index(Cart $cart, $stripeSessionId): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }


        if (!$order->getIsPaid() == 0) {
            $order->setIsPaid(1);
            $cart->Remove();

            $this->entityManager->flush();

            // TODO: Envoyer un mail à notre client pour lui confirmer sa commande
        }

        // TODO: Afficher les quelques informations de la commande de l'utilisateur 


        return $this->render('order_validate/index.html.twig', [
            "order" => $order,
        ]);
    }
}
