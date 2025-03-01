<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Candidat;
use App\Entity\Entreprise;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer quelques offres récentes à mettre en avant (par exemple, les 5 plus récentes)
        $offers = $entityManager->getRepository(Offre::class)->findBy([], ['createdAt' => 'DESC'], 5);

        // Récupérer quelques statistiques globales
        $totalUsers = $entityManager->getRepository(Users::class)->count([]);
        $totalCandidates = $entityManager->getRepository(Candidat::class)->count([]);
        $totalEnterprises = $entityManager->getRepository(Entreprise::class)->count([]);

        return $this->render('home.html.twig', [
            'offers'            => $offers,
            'totalUsers'        => $totalUsers,
            'totalCandidates'   => $totalCandidates,
            'totalEnterprises'  => $totalEnterprises,
        ]);
    }
}
