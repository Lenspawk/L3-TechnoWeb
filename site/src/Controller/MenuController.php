<?php

namespace App\Controller;

use App\Repository\PanierRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    /**
     * @param PanierRepository $shoppingBasketRepository
     * @return Response
     */
    #[IsGranted("IS_AUTHENTICATED_FULLY")]
    #[Route('/Menu', name : 'menu')]
    public function main(PanierRepository $shoppingBasketRepository): Response
    {
        $user = $this->getUser();

        $shoppingBaskets = $shoppingBasketRepository->findBy(['user' => $user]);

        $qtyTotal = 0;

        foreach($shoppingBaskets as $basket){
            $qtyTotal = $qtyTotal + $basket->getQuantity();
        }

        return $this->render('menu/menu.html.twig', [
            'user' => $user,
            'qtyTotal'=>$qtyTotal,
        ]);
    }

}
