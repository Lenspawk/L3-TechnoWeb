<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/security', name: 'security_')]

class SecurityController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function registerAction(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        if ($this->getUser() !== null) {
            throw $this->createNotFoundException('Vous êtes déjà connecté');
        }

        $user = new Utilisateur();

        $form = $this->createForm(InscriptionType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $login = $user->getLogin();
            $password = $user->getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $password);

            $loginExist = $em->getRepository(Utilisateur::class)->findOneBy(['login' => $user->getLogin()]);

            if (!$loginExist){
                $user->setPassword($hashedPassword);
                $user->setIsSuperAdmin(false);
                $user->setIsAdmin(false);
                $user->setRoles(['ROLE_USER']);

                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre inscription est confirmée');

                return $this->redirectToRoute('security_login');
            }
            else{
                if ($form->isSubmitted())
                    $this->addFlash('error', 'Erreur lors de votre inscription');
            }
        }

        $args = array('formInscription' => $form->createView());
        return $this->render('security/inscription.html.twig', $args);

    }

    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser() !== null) {
            throw $this->createNotFoundException('Vous êtes déjà connecté');
        }
        if ($this->getUser()) {
            $this->addFlash('success',"Vous êtes déjà connecté !");
            return $this->redirectToRoute('accueil_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('error',"Verifiez vos informations !");
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
