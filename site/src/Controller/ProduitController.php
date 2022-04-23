<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Produit', name: 'produit_')]

class ProduitController extends AbstractController
{
    #[Route('/magasin', name: 'magasin')]
    public function magasinAction(ManagerRegistry $doctrine): Response
    {
        if ($this->getUser() === null) {
            throw $this->createNotFoundException('Vous n\'êtes pas connecté');
        } elseif ($this->getUser() !== null && $this->getUser()->getIsSuperAdmin()) {
            throw $this->createNotFoundException('Accès bloqué en tant que super-administrateur');
        }

        $em = $doctrine->getManager();
        $produitRepository = $em->getRepository('App:Produit');
        $produits = $produitRepository->findAll();

        $args = array(
            'produits' => $produits,
            'user'=> $this->getUser()
        );

        return $this->render('produit/magasin.html.twig', $args);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/ajouter', name: 'ajouter')]
    public function registerAction(EntityManagerInterface $em, Request $request): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté');

            return $this->redirectToRoute('accueil_index');
        }
        else{
            if ($form->isSubmitted()) {
                $this->addFlash('error', 'Erreur lors de l\' ajout du produit');
            }
        }

        return $this->render('produit/ajouter.html.twig', ['formAjouterProduit' => $form->createView()]);

    }
}
