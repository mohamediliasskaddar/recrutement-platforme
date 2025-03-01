<?php

namespace App\Controller;

use App\Entity\Offre;
use App\Entity\Entreprise;
use App\Form\OffreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OffreController extends AbstractController
{
    #[Route('/entreprise/offre/creer', name: 'offer_create', methods: ['GET', 'POST'])]
    public function createOffer(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupération de l'utilisateur connecté via la session (gestion simplifiée)
        $session = $request->getSession();
        $user = $session->get('user');

        // Vérifier que l'utilisateur est connecté et possède le rôle 'entreprise'
        if (!$user || $user->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'entité Entreprise associée à cet utilisateur
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $user]);
        if (!$entreprise) {
            $this->addFlash('error', 'Aucune entreprise associée trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Création d'une nouvelle offre et initialisation des dates
        $offre = new Offre();
        $offre->setCreatedAt(new \DateTime());
        $offre->setUpdatedAt(new \DateTime());
        $offre->setEntreprise($entreprise);

        // Création et traitement du formulaire
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($offre);
            $entityManager->flush();

            $this->addFlash('success', 'Offre créée avec succès !');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        return $this->render('offre/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    //foor deleting offers
    #[Route('/entreprise/offre/supprimer/{offreId}', name: 'offer_delete', methods: ['GET'])]
    public function deleteOffer(int $offreId, EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupération de l'utilisateur connecté via la session (gestion simplifiée)
        $session = $request->getSession();
        $user = $session->get('user');

        if (!$user || $user->getRole() !== 'entreprise') {
            return $this->redirectToRoute('app_login');
        }

        // Récupérer l'entité Entreprise associée à cet utilisateur
        $entreprise = $entityManager->getRepository(Entreprise::class)
            ->findOneBy(['user' => $user]);
        if (!$entreprise) {
            $this->addFlash('error', 'Aucune entreprise associée trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Récupérer l'offre à supprimer
        $offre = $entityManager->getRepository(Offre::class)->find($offreId);
        if (!$offre) {
            $this->addFlash('error', 'Offre non trouvée.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Vérifier que l'offre appartient à l'entreprise connectée
        if ($offre->getEntreprise()->getId() !== $entreprise->getId()) {
            $this->addFlash('error', 'Vous n\'êtes pas autorisé à supprimer cette offre.');
            return $this->redirectToRoute('entreprise_dashboard');
        }

        // Supprimer l'offre
        $entityManager->remove($offre);
        $entityManager->flush();

        $this->addFlash('success', 'Offre supprimée avec succès.');
        return $this->redirectToRoute('entreprise_dashboard');
    }
}
