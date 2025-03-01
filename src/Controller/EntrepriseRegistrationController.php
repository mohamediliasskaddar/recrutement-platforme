<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseRegistrationController extends AbstractController
{
    #[Route('/inscription/entreprise', name: 'inscription_entreprise', methods: ['GET', 'POST'])]
    public function inscriptionEntreprise(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupération des champs du formulaire
            $email         = $request->request->get('email');
            $password      = $request->request->get('password');
            $nomEntreprise = $request->request->get('nomEntreprise');
            $description   = $request->request->get('description');
            $secteurs      = $request->request->get('secteurs');
            $siteWeb       = $request->request->get('siteWeb');

            // Création d'un nouvel utilisateur avec rôle entreprise
            $user = new Users();
            $user->setEmail($email);
            $user->setPassword($password); // stocké en clair pour ce projet de classe
            $user->setRole('entreprise');
            $entityManager->persist($user);
            $entityManager->flush(); // pour générer l'ID et pouvoir l'utiliser dans l'entité Entreprise

            // Création de l'entité entreprise
            $entreprise = new Entreprise();
            $entreprise->setUser($user);
            $entreprise->setNomEntreprise($nomEntreprise);
            $entreprise->setDescription($description);
            $entreprise->setSecteurs($secteurs);
            $entreprise->setSiteWeb($siteWeb);

            $entityManager->persist($entreprise);
            $entityManager->flush();

            // Message de succès et redirection vers la page de connexion
            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription entreprise
        return $this->render('auth/inscription_entreprise.html.twig');
    }
}
