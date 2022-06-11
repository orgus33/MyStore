<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/register', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $this->entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

            if (!$search_email) {
                $password = $passwordHasher->hashPassword($user, $user->getPassword());
                $user->setPassword($password);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                $mail = new Mail();
                $content = "Bienvenue " . $user->getFirstname() . " sur MyStore ! Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque rutrum risus et nunc consectetur, ut pretium diam hendrerit. Suspendisse potenti. Donec in fringilla nisi, in imperdiet magna. Suspendisse potenti. Nulla interdum nisi et est sagittis molestie. Praesent maximus arcu in mauris mattis, a cursus sapien interdum. Nullam eu euismod neque. Aenean fermentum pharetra libero pharetra egestas. Integer eu pharetra enim. Ut urna nibh, ullamcorper at ipsum vel, luctus volutpat elit. In suscipit massa a massa auctor, eget lacinia sapien bibendum.";
                $mail->send($user->getEmail(), $user->getFirstname(), "Vous êtes bien enregistrer sur MyStore !", $content);


                $notification = "Vous êtes bien enregistré ! Vous pouvez dès à présent vous connecter à votre compte";
            } else {
                $notification = "Vous êtes déjà enregistré ! Veuillez vous connecter.";
            }
        }

        return $this->render('register/index.html.twig', [
            "form" => $form->createView(),
            "notification" => $notification
        ]);
    }
}
