<?php

namespace App\Controller;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/connexion', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Traitement du formulaire de connexion (méthode POST)
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $password = $request->request->get('password');

            // Récupération de l'utilisateur correspondant à l'email
            $user = $entityManager->getRepository(Users::class)->findOneBy(['email' => $email]);

            if ($user && $user->getPassword() === $password) {
                // Simulation de la connexion en stockant l'utilisateur en session
                $request->getSession()->set('user', $user);

                // Redirection vers le dashboard approprié selon le rôle de l'utilisateur
                switch ($user->getRole()) {
                    case 'admin':
                        return $this->redirectToRoute('admin_dashboard');
                    case 'candidat':
                        return $this->redirectToRoute('candidat_dashboard');
                    case 'entreprise':
                        return $this->redirectToRoute('entreprise_dashboard');
                    default:
                        $this->addFlash('error', 'Rôle inconnu.');
                        break;
                }
            } else {
                $this->addFlash('error', 'Email ou mot de passe invalide.');
            }
        }

        // Affichage du formulaire de connexion
        // On envoie également les URL pour l'inscription candidat et entreprise
        return $this->render('auth/login.html.twig', [
            'inscription_candidat_url'   => $this->generateUrl('inscription_candidat'),
            'inscription_entreprise_url' => $this->generateUrl('inscription_entreprise'),
        ]);
    }
}
