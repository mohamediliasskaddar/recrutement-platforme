<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Notification;
use App\Entity\Offre;
use App\Entity\Candidature;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatDashboardController extends AbstractController
{
    // Page d'accueil du dashboard candidat (résumé ou accueil)
    #[Route('/dashboard/candidat', name: 'candidat_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }
        $candidat = $entityManager->getRepository(Candidat::class)->findOneBy(['user' => $sessionUser]);

        return $this->render('dashboard/candidat_dashboard.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    // Page de profil du candidat
    #[Route('/dashboard/candidat/profile', name: 'candidat_profile')]
    public function profile(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }
        $candidat = $entityManager->getRepository(Candidat::class)->findOneBy(['user' => $sessionUser]);
        return $this->render('dashboard/candidat_profile.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    // Page des notifications
    #[Route('/dashboard/candidat/notifications', name: 'candidat_notifications')]
    public function notifications(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }
        $notifications = $entityManager->getRepository(Notification::class)->findBy(['user' => $sessionUser]);
        $candidat = $entityManager->getRepository(Candidat::class)->findOneBy(['user' => $sessionUser]);
        return $this->render('dashboard/candidat_notifications.html.twig', [
            'candidat'      => $candidat,
            'notifications' => $notifications,
        ]);
    }

    // Page des offres disponibles
    #[Route('/dashboard/candidat/offres', name: 'candidat_offres')]
    public function offres(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }
        $offres = $entityManager->getRepository(Offre::class)->findAll();
        $candidat = $entityManager->getRepository(Candidat::class)->findOneBy(['user' => $sessionUser]);
        return $this->render('dashboard/candidat_offres.html.twig', [
            'candidat' => $candidat,
            'offres'   => $offres,
        ]);
    }

    // Page des candidatures effectuées par le candidat
    #[Route('/dashboard/candidat/candidatures', name: 'candidat_candidatures')]
    public function candidatures(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }
        $candidat = $entityManager->getRepository(Candidat::class)->findOneBy(['user' => $sessionUser]);
        $candidatures = $entityManager->getRepository(Candidature::class)->findBy(['candidat' => $candidat]);
        return $this->render('dashboard/candidat_candidatures.html.twig', [
            'candidat'    => $candidat,
            'candidatures' => $candidatures,
        ]);
    }
}
