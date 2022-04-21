<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Formulaire', name: 'formulaire_')]

class FormController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function registerAction(EntityManagerInterface $em, Request $request): Response
    {
        $user = new Utilisateur();

        if ($this->getUser() !== null) {
            throw $this->createNotFoundException('Vous êtes déjà connecté');
        }

        $form = $this->createForm(UtilisateurType::class, $user);
        $form->add('send', SubmitType::class, ['label' => 'Valider l\'inscription']);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setIsSuperAdmin(false);
            $user->setIsAdmin(false);

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Votre inscription est confirmée');

            return $this->redirectToRoute('accueil_index');
        }

        if ($form->isSubmitted())
            $this->addFlash('error', 'Erreur lors de votre inscription');

        $args = array('formInscription' => $form->createView());
        return $this->render('form/inscription.html.twig', $args);

    }

    #[Route('/connexion', name: 'connexion')]
    public function connexionAction(EntityManagerInterface $em, Request $request): Response
    {

        if ($this->getUser() !== null) {
            throw $this->createNotFoundException('Vous êtes déjà connecté');
        }

        return $this->render('form/connexion.html.twig');

    }
}
