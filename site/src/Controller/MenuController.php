<?php

namespace App\Controller;


use App\Entity\Utilisateur;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/Menu', name : 'menu_')]

class MenuController extends AbstractController
{
    #[Route('', name: 'main')]
    public function mainAction(ManagerRegistry $doctrine): Response
    {

        //$userId = $this->getUser()->getId();

        //$em = $doctrine->getManager();
        //$utilisateurRepository = $em->getRepository('App:Utilisateur');
        //$utilisateur = $utilisateurRepository->find($userId);
        //$panierRepository = $em->getRepository('App:Panier');
        //$panier = count($panierRepository)->findAll();

        //$args = array(
            //'user'=> $utilisateur,
            //'user'=> $this->getUser(),
            //'nbProducts' => $panier
        //);

        return $this->render('menu/menu.html.twig'/*, $args*/);
    }

    #[Route('/ajoutendur', name: 'ajoutendur')]
    public function ajoutendurAction(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $date = new \DateTime('@'.strtotime('now'));

        $user = new Utilisateur();
        $user->setLogin('bioxtech')
            ->setPassword('guigui')
            ->setSurname('Guillaume')
            ->setFirstname('Porro')
            //->setDateOfBirth($date)
            ->setIsAdmin(true)
            ->setIsSuperAdmin(false);
        dump($user);

        $em->persist($user);
        $em->flush();
        dump($user);

        return $this->redirectToRoute('accueil_index');
    }

    #[Route('/effacerendur', name: 'effacerendur')]
    public function effacerendurAction(ManagerRegistry $doctrine): Response
    {
        $id = 1;
        $em = $doctrine->getManager();
        $utilisateurRepository = $em->getRepository('App:Utilisateur');

        $utilisateur = $utilisateurRepository->find($id);

        $em->remove($utilisateur);
        $em->flush();

        return $this->redirectToRoute('accueil_index');
    }


}
