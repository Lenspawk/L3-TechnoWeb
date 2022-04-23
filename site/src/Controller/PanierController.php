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
        //if (is_null($user))
        //    throw new NotFoundHttpException('Utilisateur inexistant');
        //elseif ($this->getUser() === null){
        //    throw $this->createNotFoundException('Vous n\'êtes pas connecté');
        //}
        //elseif ($this->getUser() !== null && $this->getUser()->getIsSuperAdmin()) {
        //    throw $this->createNotFoundException('Accès bloqué en tant que super-administrateur');
        //}

        //$productsRepository = $em->getRepository(Produit::class);
        $products = $produitRepository->findAll();

        //$utilisateurRepository = $em->getRepository(Utilisateur::class);


        //$shoppingBasketRepository = $em->getRepository(Panier::class);
        $shoppingBaskets = $shoppingBasketRepository->findBy(['user' => $user]);


        $priceTotal = 0;
        $qtyTotal = 0;
        /*$args = array(
            'user' => $user,
            'shoppingBaskets' => $shoppingBaskets,
            'products' => $products,
            'priceTotal' => $priceTotal,
            'qtyTotal' => $qtyTotal
        );*/

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

        /*if ($request->isMethod('post')) {

            $produitRepository = $em->getRepository('App:Produit');

            $userRepository = $em->getRepository('App:Utilisateur');
            $user = $userRepository->find($userId);


            $panierRepository = $em->getRepository('App:Panier');
            $paniers = $panierRepository->findBy(['user' => $this->getUser()]);

            foreach ($_POST['quantite'] as $produitId => $wantedQty) {


                if ($wantedQty > 0) {
                    $produit = $produitRepository->find($produitId);

                    $newPanier = new Panier();
                    $newPanier->setProduct($produit);
                    $newPanier->setUser($this->getUser());
                    $newPanier->setQuantity($wantedQty);

                    foreach ($paniers as $panier) {
                        if ($panier->getProduct()->getId() === $produitId) {
                            $newPanier = $panier;
                            $newPanier->setQuantite($newPanier->getQuantite() + $wantedQty);
                            break;
                        }
                    }

                    $em->persist($newPanier);

                    $produit->setStock($produit->getStock() - $wantedQty);
                    $em->persist($produit);
                }
            }
        }*/

        //$this->addFlash('success', 'Produits ajoutés au panier');
        //$em->flush();

        //return $this->redirectToRoute('panier_list');
    }

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
}

