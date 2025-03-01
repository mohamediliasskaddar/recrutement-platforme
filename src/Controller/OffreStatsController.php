<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Candidature;
use App\Entity\Entreprise;
use App\Entity\Candidat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreStatsController extends AbstractController
{
    #[Route('/entreprise/offre/{offreId}/stats', name: 'offre_stats', methods: ['GET'])]
    public function stats(int $offreId, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérifier l'utilisateur connecté (gestion simplifiée via la session)
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'entreprise associée à l'utilisateur connecté
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);
        if (!$entreprise) {
            $this->addFlash('error', 'Entreprise non trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Récupérer l'offre en question
        $offre = $entityManager->getRepository(Offre::class)->find($offreId);
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Vérifier que l'offre appartient bien à l'entreprise connectée
        if ($offre->getEntreprise()->getId() !== $entreprise->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à consulter les statistiques pour cette offre.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Récupérer les candidatures pour cette offre
        $candidatures = $entityManager->getRepository(Candidature::class)
            ->findBy(['offre' => $offre]);

        // Calcul des statistiques
        $totalCandidatures = count($candidatures);
        $nbFemmes = 0;
        $nbHommes = 0;
        $candidatsDetails = [];

        // Dans votre méthode stats() existante :
        foreach ($candidatures as $candidature) {
            $candidat = $candidature->getCandidat();
            $sexe = $candidat->getSexe();
            if (strtolower($sexe) === 'femme') {
                $nbFemmes++;
            } elseif (strtolower($sexe) === 'homme') {
                $nbHommes++;
            }
            $candidatsDetails[] = [
                'id'         => $candidat->getId(), // Ajout de l'ID
                'nomcomplet' => $candidat->getNomcomplet(),
                'email'      => $candidat->getUser()->getEmail(),
                'cvPath'     => $candidat->getCvPath(),
            ];
        }


        // Calculer le nombre de jours restants pour la fermeture de l'offre
        $daysRemaining = null;
        $now = new \DateTime();
        $dateFin = $offre->getDateFin();
        if ($dateFin) {
            $interval = $now->diff($dateFin);
            // Si l'offre est encore ouverte (dateFin > now), sinon 0
            $daysRemaining = $interval->invert ? 0 : $interval->days;
        }

        return $this->render('offre/stats.html.twig', [
            'offre'             => $offre,
            'totalCandidatures' => $totalCandidatures,
            'nbFemmes'          => $nbFemmes,
            'nbHommes'          => $nbHommes,
            'candidatsDetails'  => $candidatsDetails,
            'daysRemaining'     => $daysRemaining,
        ]);
    }
    //this one is for sending mailes for candidates who are admittted
    #[Route('/entreprise/offre/{offreId}/send-mail/{candidateId}', name: 'offre_send_mail', methods: ['GET'])]
    public function sendMail(
        int $offreId,
        int $candidateId,
        EntityManagerInterface $entityManager,
        Request $request,
        MailerInterface $mailer
    ): Response {
        // Vérification de l'utilisateur connecté et de son rôle entreprise
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $sessionUser]);
        if (!$entreprise) {
            $this->addFlash('error', 'Entreprise non trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }
        $offre = $entityManager->getRepository(Offre::class)->find($offreId);
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }
        if ($offre->getEntreprise()->getId() !== $entreprise->getId()) {
            $this->addFlash('error', "Vous n'êtes pas autorisé à envoyer un email pour cette offre.");
            return $this->redirectToRoute('entreprise_dashboard');
        }
        $candidate = $entityManager->getRepository(Candidat::class)->find($candidateId);
        if (!$candidate) {
            $this->addFlash('error', 'Candidat non trouvé.');
            return $this->redirectToRoute('offre_stats', ['offreId' => $offreId]);
        }

        // Génération aléatoire du lieu d'entretien
        $locations = [
            "123 Rue de Paris, Paris",
            "456 Avenue de Lyon, Lyon",
            "789 Boulevard de Marseille, Marseille",
            "321 Route de Bordeaux, Bordeaux",
            "654 Rue de Lille, Lille"
        ];
        $randomLocation = $locations[array_rand($locations)];

        // Générer une date et heure d'entretien aléatoire (entre 3 et 10 jours dans le futur)
        $daysOffset = rand(3, 10);
        $interviewDate = new \DateTime("+$daysOffset days");
        $hour = rand(9, 17);
        $minute = rand(0, 59);
        $interviewDate->setTime($hour, $minute);
        $formattedInterviewDate = $interviewDate->format('d/m/Y H:i');

        // Générer aléatoirement la liste des documents à apporter
        $documentsOptions = ["CV", "Lettre de motivation", "Portfolio", "Références", "Diplômes"];
        shuffle($documentsOptions);
        $requiredDocuments = implode(", ", array_slice($documentsOptions, 0, rand(2, 3)));

        // Préparer l'e-mail en utilisant un template Twig
        $email = (new TemplatedEmail())
            //->from($entreprise->getUser()->getEmail())  // Adresse de l'entreprise
            ->from("medpc59@gmail.com")
            ->to($candidate->getUser()->getEmail())
            ->subject('Invitation à un entretien pour l\'offre : ' . $offre->getTitre())
            ->htmlTemplate('emails/interview_invitation.html.twig')
            ->context([
                'candidate'         => $candidate,
                'offre'             => $offre,
                'entreprise'        => $entreprise,
                'interviewLocation' => $randomLocation,
                'interviewDate'     => $formattedInterviewDate,
                'requiredDocuments' => $requiredDocuments,
            ]);
        //dd($email);

        $mailer->send($email);
        //dd("Email sent");

        $this->addFlash('success', 'E-mail envoyé avec succès au candidat.');
        return $this->redirectToRoute('offre_stats', ['offreId' => $offreId]);
    }
}
