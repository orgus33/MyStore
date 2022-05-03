<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\RegisterType;

class RegisterController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function index(): Response
    {

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        return $this->render('register/index.html.twig', [
            "form" => $form->createView()
        ]);
    }
}
