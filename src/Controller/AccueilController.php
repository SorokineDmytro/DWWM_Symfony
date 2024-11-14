<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        // $file = "accueil/index.html.twig";
        // $variables = [
        //     'title' => 'Accueil',
        //     'controller_name' => 'AccueilController',
        // ];
        // return $this->render($file, $variables);

        return $this->render('accueil/index.html.twig', [
            'title' => 'Accueil',
            'controller_name' => 'AccueilController',
        ]);

    }
}
