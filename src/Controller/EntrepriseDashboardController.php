<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Entity\Notification;
use App\Entity\Offre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EntrepriseDashboardController extends AbstractController
{
    // Page d'accueil du dashboard entreprise (optionnelle)
    #[Route('/dashboard/entreprise', name: 'entreprise_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupération de l'utilisateur connecté via la session
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }
        // Récupérer l'entité Entreprise associée à l'utilisateur connecté
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);

        return $this->render('dashboard/entreprise_dashboard.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }

    // Page Notifications
    #[Route('/dashboard/entreprise/notifications', name: 'entreprise_notifications')]
    public function notifications(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }
        $notifications = $entityManager->getRepository(Notification::class)
            ->findBy(['user' => $sessionUser]);
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);
        return $this->render('dashboard/entreprise_notifications.html.twig', [
            'entreprise'    => $entreprise,
            'notifications' => $notifications,
        ]);
    }

    // Page Mes Offres
    #[Route('/dashboard/entreprise/mes-offres', name: 'entreprise_mes_offres')]
    public function mesOffres(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);
        $mesOffres = $entityManager->getRepository(Offre::class)
            ->findBy(['entreprise' => $entreprise]);
        return $this->render('dashboard/entreprise_mes_offres.html.twig', [
            'entreprise' => $entreprise,
            'mesOffres'  => $mesOffres,
        ]);
    }

    // Page Toutes les Offres
    #[Route('/dashboard/entreprise/toutes-offres', name: 'entreprise_all_offres')]
    public function allOffres(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);
        $allOffres = $entityManager->getRepository(Offre::class)->findAll();
        return $this->render('dashboard/entreprise_all_offres.html.twig', [
            'entreprise' => $entreprise,
            'allOffres'  => $allOffres,
        ]);
    }
}
