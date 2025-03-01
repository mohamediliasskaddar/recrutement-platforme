<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Entity\Offre;
use App\Entity\Candidat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatureController extends AbstractController
{
    #[Route('/candidature/postuler/{offreId}', name: 'candidature_postuler', methods: ['GET'])]
    public function postuler(int $offreId, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupérer l'utilisateur connecté via la session (gestion simplifiée)
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'entité Candidat associée à l'utilisateur connecté
        $candidat = $entityManager->getRepository(Candidat::class)
            ->findOneBy(['user' => $sessionUser]);
        if (!$candidat) {
            $this->addFlash('error', 'Profil candidat non trouvé.');
            return $this->redirectToRoute('candidat_dashboard');
        }

        // Récupérer l'offre à laquelle postuler
        $offre = $entityManager->getRepository(Offre::class)->find($offreId);
        if (!$offre) {
            $this->addFlash('error', 'Offre introuvable.');
            return $this->redirectToRoute('candidat_dashboard');
        }

        // Vérifier si une candidature existe déjà pour ce candidat et cette offre
        $existingCandidature = $entityManager->getRepository(Candidature::class)
            ->findOneBy(['candidat' => $candidat, 'offre' => $offre]);
        if ($existingCandidature) {
            $this->addFlash('info', 'Vous avez déjà postulé pour cette offre.');
            return $this->redirectToRoute('candidat_dashboard');
        }

        // Créer une nouvelle candidature
        $candidature = new Candidature();
        $candidature->setCandidat($candidat);
        $candidature->setOffre($offre);
        $candidature->setStatut('en attente');
        $candidature->setDatePostulation(new \DateTime());

        $entityManager->persist($candidature);
        $entityManager->flush();

        $this->addFlash('success', 'Votre candidature a été enregistrée.');
        return $this->redirectToRoute('candidat_dashboard');
    }
}
