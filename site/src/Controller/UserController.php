<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Utilisateur', name: 'user_')]

class UserController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') or is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('', name: 'index')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $utilisateurRepository->findAll(),
            'currentUser' => $this->getUser(),
        ]);
    }

    #[IsGranted("ROLE_ADMIN")]
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => "\d+"])]
    public function delete(Utilisateur $user, UtilisateurRepository $utilisateurRepository, EntityManagerInterface $em): Response
    {
        $hasSAAccess = $this->isGranted('ROLE_SUPERADMIN');

        if ($hasSAAccess or $user === $this->getUser()){
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce compte');
            return $this->redirectToRoute('user_index');
        }

        $em->persist($user);

        $em->remove($user);
        $em->flush();

        $this->addFlash('info', 'Utilisateur supprimé');

        return $this->redirectToRoute('user_index');
    }
}
