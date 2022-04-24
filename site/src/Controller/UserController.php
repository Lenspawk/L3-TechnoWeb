<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/Utilisateur', name: 'user_')]

class UserController extends AbstractController
{
    #[Security("is_granted('ROLE_ADMIN') and is_granted('IS_AUTHENTICATED_FULLY')")]
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

    #[Security("is_granted('ROLE_SUPERADMIN') and is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/addadmin', name: 'addadmin')]
    public function addAdmin(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Utilisateur();

        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $password = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $password);

            $loginExist = $em->getRepository('App:Utilisateur')->findOneBy(['login' => $user->getLogin()]);

            if (!$loginExist){
                $user->setPassword($hashedPassword);
                $user->setIsSuperAdmin(false);
                $user->setIsAdmin(true);
                $user->setRoles(['ROLE_ADMIN']);

                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Administrateur crée');

                return $this->redirectToRoute('user_addadmin');
            }
            else{
                if ($form->isSubmitted())
                    $this->addFlash('error', 'Erreur lors de la création de l\'administrateur');
            }
        }

        return $this->render('user/addadmin.html.twig', ['formAddAdmin' => $form->createView()]);
    }
}
