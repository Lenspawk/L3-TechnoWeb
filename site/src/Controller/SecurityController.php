<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurType;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


#[Route('/security', name: 'security_')]

class SecurityController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return Response
     */
    #[Route('/register', name: 'register')]
    public function register(EntityManagerInterface $em, Request $request, UserPasswordHasherInterface $passwordHasher) : Response

    {
        if ($this->getUser() !== null) {
            throw $this->createNotFoundException('Vous êtes déjà connecté');
        }

        $user = new Utilisateur();

        $form = $this->createForm(UtilisateurType::class, $user);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $password = $user->getPassword();

                $user->setPassword($passwordHasher->hashPassword($user, $password));
                $user->setIsSuperAdmin(true);
                $user->setRoles(['ROLE_SUPERADMIN']);

                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre inscription est confirmée');

                return $this->redirectToRoute('index');
            }

        else{
            if ($form->isSubmitted()) {
                $this->addFlash('error', 'Erreur lors de votre inscription');
            }
        }

        return $this->renderForm('security/register.html.twig', ['formInscription' => $form]);

    }


    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            $this->addFlash('success',"Vous êtes déjà connecté !");
            return $this->redirectToRoute('index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        if ($error) {
            $this->addFlash('error',"Verifiez vos informations !");
        }

        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }


    /**
     * @return void
     */
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
        throw new LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

}
