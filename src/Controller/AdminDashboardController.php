<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Entreprise;
use App\Entity\Offre;
use App\Entity\Notification;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier que l'utilisateur connecté est bien un admin (ici, gestion simplifiée via la session)
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('admindashboard/dashboard.html.twig', [
            'admin' => $sessionUser,
        ]);
    }

    #[Route('/admin/candidates', name: 'admin_candidates')]
    public function candidates(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }
        $candidates = $entityManager->getRepository(Candidat::class)->findAll();
        return $this->render('admindashboard/candidates.html.twig', [
            'candidates' => $candidates,
        ]);
    }

    #[Route('/admin/enterprises', name: 'admin_enterprises')]
    public function enterprises(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }
        $enterprises = $entityManager->getRepository(Entreprise::class)->findAll();
        return $this->render('admindashboard/enterprises.html.twig', [
            'enterprises' => $enterprises,
        ]);
    }

    #[Route('/admin/offers', name: 'admin_offers')]
    public function offers(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }
        $offers = $entityManager->getRepository(Offre::class)->findAll();
        return $this->render('admindashboard/offers.html.twig', [
            'offers' => $offers,
        ]);
    }

    #[Route('/admin/notifications', name: 'admin_notifications')]
    public function notifications(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }
        $notifications = $entityManager->getRepository(Notification::class)->findAll();
        return $this->render('admindashboard/notifications.html.twig', [
            'notifications' => $notifications,
        ]);
    }

    #[Route('/admin/enterprise/{id}/delete', name: 'admin_enterprise_delete', methods: ['GET'])]
    public function deleteEnterprise(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier que l'utilisateur connecté est bien un admin
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'entreprise par son id
        $enterprise = $entityManager->getRepository(Entreprise::class)->find($id);
        if (!$enterprise) {
            $this->addFlash('error', 'Entreprise non trouvée.');
            return $this->redirectToRoute('admin_enterprises');
        }

        try {
            $entityManager->remove($enterprise);
            $entityManager->flush();
            $this->addFlash('success', 'Entreprise supprimée avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression de l\'entreprise.');
        }

        return $this->redirectToRoute('admin_enterprises');
    }

    #[Route('/admin/offer/{id}/delete', name: 'admin_offer_delete', methods: ['GET'])]
    public function deleteOffer(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier que l'utilisateur connecté est bien un admin
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'offre par son id
        $offer = $entityManager->getRepository(Offre::class)->find($id);
        if (!$offer) {
            $this->addFlash('error', 'Offre non trouvée.');
            return $this->redirectToRoute('admin_offers');
        }

        try {
            $entityManager->remove($offer);
            $entityManager->flush();
            $this->addFlash('success', 'Offre supprimée avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression de l\'offre.');
        }

        return $this->redirectToRoute('admin_offers');
    }

    #[Route('/admin/candidate/{id}/delete', name: 'admin_candidate_delete', methods: ['GET'])]
    public function deleteCandidate(int $id, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier que l'utilisateur connecté est bien un admin
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le candidat par son id
        $candidate = $entityManager->getRepository(Candidat::class)->find($id);
        if (!$candidate) {
            $this->addFlash('error', 'Candidat non trouvé.');
            return $this->redirectToRoute('admin_candidates');
        }

        try {
            $entityManager->remove($candidate);
            $entityManager->flush();
            $this->addFlash('success', 'Candidat supprimé avec succès.');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Une erreur est survenue lors de la suppression du candidat.');
        }

        return $this->redirectToRoute('admin_candidates');
    }


}
