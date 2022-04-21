<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Accueil', name : 'accueil_')]

class AccueilController extends AbstractController
{
    #[Route('', name: 'index')]
    public function indexAction(): Response
    {
        return $this->render('accueil/index.html.twig', [
            'user' => $this->getUser(),
            'welcome' => 'Nous te souhaitons la bienvenue sur le site',
            'nameSite' => 'Simon&Guillaume',
        ]);
    }
}
