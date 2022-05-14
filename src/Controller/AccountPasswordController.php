<?php

namespace App\Controller;

use App\Entity\User;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/ModifyPassword', name: 'app_account_password')]
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;

        $user = $this->getUser();

        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $old_pwd = $form->get("old_password")->getData();

            if ($encoder->isPasswordValid($user, $old_pwd)) {
                $new_password = $form->get("new_password")->getData();

                $password = $encoder->hashPassword($user, $new_password);

                $user->setPassword($password);

                $this->entityManager->flush();
                $notification = "Votre mot de passe a bien été mis à jour !";
            } else {
                die("Ca ne marche pas !");
            }
        }

        return $this->render('account/password.html.twig', [
            "form" => $form->createView(),
            "notification" => $notification
        ]);
    }
}
