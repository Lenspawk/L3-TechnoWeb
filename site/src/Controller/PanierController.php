<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\Utilisateur;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\UtilisateurRepository;
use App\Service\AlertServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Panier', name: 'panier_')]

class PanierController extends AbstractController
{
    private AlertServiceInterface $alertService;

    public function __construct(AlertServiceInterface $alertService)
    {
        $this->alertService = $alertService;
    }

    #[Route('/list', name: 'list')]
    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    public function list(EntityManagerInterface $em, ProduitRepository $produitRepository, PanierRepository $shoppingBasketRepository): Response
    {
        $user = $this->getUser();

        $products = $produitRepository->findAll();

        $shoppingBaskets = $shoppingBasketRepository->findBy(['user' => $user]);

        $priceTotal = 0;
        $qtyTotal = 0;

        return $this->render('panier/list.html.twig', [
            'user' => $user,
            'shoppingBaskets' => $shoppingBaskets,
            'products' => $products,
            'priceTotal' => $priceTotal,
            'qtyTotal' => $qtyTotal,
        ]);
    }


    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    #[Route('/ajout', name: 'ajout')]
    public function add(EntityManagerInterface $em, Request $request, ProduitRepository $produitRepository) : Response
    {
        if (!$request->isMethod('POST')){
            throw $this->createNotFoundException('Erreur');
        }

        $wantedQty = $request->request->get('quantite');

        /** @var Utilisateur  $user */
        $user = $this->getUser();

        foreach($wantedQty as $key => $value){
            if ($value > 0){
                $produit = $produitRepository->find($key);

                $panier = new Panier();
                $panier->setProduct($produit);
                $panier->setUser($user);
                $panier->setQuantity($value);

                $em->persist($panier);

                $produit->setStock($produit->getStock() - $value);
                $em->persist($produit);
            }
        }

        //$this->addFlash('success', 'Produits ajoutés au panier');
        $this->alertService->success('Produits ajoutés au panier');
        $em->flush();

        return $this->redirectToRoute('panier_list');
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    #[Route('/suppression/{id}', name: 'suppression', requirements: ['id' => "\d+"])]
    public function delete(Produit $produit, PanierRepository $panierRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $basket = $panierRepository->findOneBy(['user'=>$user, 'product'=>$produit]);

        $quantite = $basket->getQuantity();
        $produit->setStock($produit->getStock() + $quantite);
        $em->persist($produit);

        $em->remove($basket);
        $em->flush();

        $this->alertService->info('Produit supprimé');

        return $this->redirectToRoute('panier_list');
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    #[Route('/vider', name: 'vider')]
    public function empty(ProduitRepository $produitRepository, PanierRepository $panierRepository, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $baskets = $panierRepository->findBy(['user'=>$user]);


        foreach ($baskets as $basket){
            $produit = $basket->getProduct();

            $produit->setStock($produit->getStock() + $basket->getQuantity());

            $em->persist($produit);

            $em->remove($basket);
        }

        $em->flush();

        $this->alertService->info('Panier vidé');

        return $this->redirectToRoute('panier_list');
    }

    #[Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')")]
    #[Route('/commander', name: 'commander')]
    public function command(PanierRepository $panierRepository, EntityManagerInterface $em) : Response
    {
        $user = $this->getUser();
        $baskets = $panierRepository->findBy(['user'=>$user]);


        foreach ($baskets as $basket){
            $em->remove($basket);
        }

        $em->flush();

        $this->alertService->info('Commande confirmée');

        return $this->redirectToRoute('panier_list');
    }
}

