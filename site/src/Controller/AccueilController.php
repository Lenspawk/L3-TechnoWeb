<?php

namespace App\Controller;

use App\Service\MyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    /**
     * @param MyService $myService
     * @return Response
     */
    #[Route('/Accueil', name : 'index')]
    #[Route('', name : 'index')]
    public function index(MyService $myService): Response
    {
        return $this->render('accueil/index.html.twig', [
            'user' => $this->getUser(),
            'tab' => $myService->sommeTab([1,5,10,17,24,30]),
        ]);
    }
}
