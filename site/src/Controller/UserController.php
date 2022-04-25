<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
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
    /**
     * @param UtilisateurRepository $utilisateurRepository
     * @return Response
     */
    #[Security("is_granted('ROLE_ADMIN') and is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('', name: 'index')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $utilisateurRepository->findAll(),
            'currentUser' => $this->getUser(),
        ]);
    }

    /**
     * @param Utilisateur $user
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Security("is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/edit/{id}', name: 'edit', requirements: ['id' => "\d+"])]
    public function edit(Utilisateur $user, EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $user->getPassword();

            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $em->flush();

            $this->addFlash('success', 'Informations modifiées');
            return $this->redirectToRoute('menu');
        }
        else{
            if ($form->isSubmitted())
                $this->addFlash('error', 'Erreur lors de l\'edition du profil');
        }

        return $this->renderForm('user/edit.html.twig', ['formEdition' => $form]);

    }


    /**
     * @param Utilisateur $user
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[IsGranted("ROLE_ADMIN")]
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => "\d+"])]
    public function delete(Utilisateur $user, EntityManagerInterface $em): Response
    {
        if ($this->isGranted('ROLE_SUPERADMIN') || $user === $this->getUser()) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer ce compte');
            return $this->redirectToRoute('user_index');
        }

        $em->persist($user);

        $em->remove($user);
        $em->flush();

        $this->addFlash('info', 'Utilisateur supprimé');

        return $this->redirectToRoute('user_index');
    }


    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Security("is_granted('ROLE_SUPERADMIN') and is_granted('IS_AUTHENTICATED_FULLY')")]
    #[Route('/addadmin', name: 'addadmin')]
    public function addadmin(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $password = $user->getPassword();

            $user->setPassword($passwordHasher->hashPassword($user, $password));
            $user->setIsAdmin(true);
            $user->setRoles(['ROLE_ADMIN']);

            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Administrateur crée');

            return $this->redirectToRoute('user_addadmin');
            }
        else{
            if ($form->isSubmitted()) {
                $this->addFlash('error', 'Erreur lors de la création de l\'administrateur');
            }
        }

        return $this->renderForm('user/addadmin.html.twig', ['formAddAdmin' => $form]);
    }
}
