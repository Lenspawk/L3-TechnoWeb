<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Form\ProduitType;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Produit', name: 'produit_')]

class ProduitController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Security(" not is_granted('ROLE_SUPERADMIN')")]
    #[Route('/index', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $produitRepository = $em->getRepository(Produit::class);
        $produits = $produitRepository->findAll();

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
            'user' => $this->getUser(),
        ]);
    }


    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param ProduitRepository $produitRepository
     * @return Response
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/ajouter', name: 'ajouter')]
    public function add(EntityManagerInterface $em, Request $request, ProduitRepository $produitRepository): Response
    {
        $produit = new Produit();

        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach($produitRepository->findAll() as $prod){
                if ($prod->getLabel() === $produit->getLabel() && $prod->getPrice() === $produit->getPrice()){
                    $stockTemp = $prod->getStock();
                    $produit = $prod;
                    $produit->setStock($produit->getStock() + $stockTemp);
                }
            }

            $em->persist($produit);
            $em->flush();
            $this->addFlash('success', 'Produit ajouté');

            return $this->redirectToRoute('index');
        }
        else{
            if ($form->isSubmitted()) {
                $this->addFlash('error', 'Erreur lors de l\' ajout du produit');
            }
        }

        return $this->renderForm('produit/ajouter.html.twig', ['formAjouterProduit' => $form]);

    }
}
