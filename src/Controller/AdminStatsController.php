<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Entreprise;
use App\Entity\Offre;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminStatsController extends AbstractController
{
    #[Route('/admin/statistics', name: 'admin_statistics')]
    public function statistics(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Vérification de l'utilisateur connecté (doit être admin)
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'admin') {
            return $this->redirectToRoute('app_login');
        }

        // 1. Récupérer les totaux
        $totalUsers = $entityManager->getRepository(Users::class)->count([]);
        $totalCandidates = $entityManager->getRepository(Candidat::class)->count([]);
        $totalEnterprises = $entityManager->getRepository(Entreprise::class)->count([]);
        $totalOffers = $entityManager->getRepository(Offre::class)->count([]);

        // 2. Distribution des candidats par sexe
        $dql = "SELECT c.sexe AS sexe, COUNT(c.id) AS count FROM App\Entity\Candidat c GROUP BY c.sexe";
        $query = $entityManager->createQuery($dql);
        $genderDistribution = $query->getResult();

        $genderLabels = [];
        $genderData = [];
        foreach ($genderDistribution as $row) {
            $label = $row['sexe'] ? $row['sexe'] : 'Non spécifié';
            $genderLabels[] = $label;
            $genderData[] = (int) $row['count'];
        }

        // 3. Histogramme des comptes créés par semaine (en utilisant la colonne date_inscription de Users)
        $conn = $entityManager->getConnection();
        $sql = "SELECT EXTRACT(WEEK FROM date_inscription) AS week, COUNT(id) AS count 
                FROM users 
                WHERE date_inscription IS NOT NULL
                GROUP BY week
                ORDER BY week ASC";
        $stmt = $conn->executeQuery($sql);
        $weeklyAccounts = $stmt->fetchAllAssociative();

        $weekLabels = [];
        $weekData = [];
        foreach ($weeklyAccounts as $row) {
            $weekLabels[] = "Semaine " . $row['week'];
            $weekData[] = (int) $row['count'];
        }

        // 4. Préparation des données pour le second graphique
        // Ce graphique comparera le nombre de comptes candidats et entreprises.
        $accountsLabels = json_encode(["Candidats", "Entreprises"]);
        $accountsData = json_encode([$totalCandidates, $totalEnterprises]);

        return $this->render('admindashboard/statistics.html.twig', [
            'totalUsers'        => $totalUsers,
            'totalCandidates'   => $totalCandidates,
            'totalEnterprises'  => $totalEnterprises,
            'totalOffers'       => $totalOffers,
            'genderLabels'      => json_encode($genderLabels),
            'genderData'        => json_encode($genderData),
            'weekLabels'        => json_encode($weekLabels),
            'weekData'          => json_encode($weekData),
            'accountsLabels'    => $accountsLabels,
            'accountsData'      => $accountsData,
        ]);
    }
}
