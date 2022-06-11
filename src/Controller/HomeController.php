<?php

namespace App\Controller;

use App\Classe\Mail;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        $mail = new Mail();
        $mail->send('bastien.b200405@gmail.com', 'Bastien', 'Mon premier mail', "Bonjour Bastien, j'espère que tu vas bien");

        return $this->render('home/index.html.twig');
    }
}
