<?php

namespace App\Controller;

use App\Form\EntrepriseType;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseProfileController extends AbstractController
{
    #[Route('/profile/entreprise', name: 'entreprise_profile', methods: ['GET'])]
    public function profile(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupération de l'utilisateur connecté via la session (gestion simplifiée)
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'entité Entreprise associée à l'utilisateur connecté
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);

        if (!$entreprise) {
            $this->addFlash('error', 'Profil entreprise non trouvé.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        return $this->render('dashboard/entreprise_profile.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }
    #[Route('/profile/entreprise/modifier', name: 'entreprise_profile_edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }

        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);

        if (!$entreprise) {
            $this->addFlash('error', 'Profil entreprise non trouvé.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('entreprise_profile');
        }

        return $this->render('dashboard/entreprise_profile_edit.html.twig', [
            'entreprise' => $entreprise,
            'form'       => $form->createView(),
        ]);
    }
}
