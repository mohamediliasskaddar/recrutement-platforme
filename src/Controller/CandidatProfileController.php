<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Form\CandidatType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatProfileController extends AbstractController
{
    #[Route('/profile/candidat', name: 'candidat_profile', methods: ['GET'])]
    public function profile(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Récupération de l'utilisateur connecté via la session (gestion simplifiée)
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }

        $candidat = $entityManager->getRepository(Candidat::class)
            ->findOneBy(['user' => $sessionUser]);

        if (!$candidat) {
            $this->addFlash('error', 'Profil candidat non trouvé.');
            return $this->redirectToRoute('candidat_dashboard');
        }

        return $this->render('dashboard/candidat_profile.html.twig', [
            'candidat' => $candidat,
        ]);
    }

    #[Route('/profile/candidat/modifier', name: 'candidat_profile_edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $entityManager, Request $request): Response
    {
        $sessionUser = $request->getSession()->get('user');
        if (!$sessionUser || $sessionUser->getRole() !== 'candidat') {
            return $this->redirectToRoute('app_login');
        }

        $candidat = $entityManager->getRepository(Candidat::class)
            ->findOneBy(['user' => $sessionUser]);

        if (!$candidat) {
            $this->addFlash('error', 'Profil candidat non trouvé.');
            return $this->redirectToRoute('candidat_dashboard');
        }

        $form = $this->createForm(\App\Form\CandidatType::class, $candidat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Gestion du fichier CV
            $cvFile = $form->get('cvFile')->getData();
            if ($cvFile) {
                // Chemin vers le répertoire d'upload du CV
                $cvDirectory = $this->getParameter('cv_directory'); // Par exemple, défini dans parameters.yaml

                // Optionnel : Si un ancien CV existe, on peut le déplacer vers un répertoire d'archive ou le supprimer
                if ($candidat->getCvPath() && file_exists($this->getParameter('kernel.project_dir') . '/public/' . $candidat->getCvPath())) {
                    $archiveDir = $cvDirectory . '/archive';
                    if (!is_dir($archiveDir)) {
                        mkdir($archiveDir, 0777, true);
                    }
                    rename($this->getParameter('kernel.project_dir') . '/public/' . $candidat->getCvPath(), $archiveDir . '/' . basename($candidat->getCvPath()));
                }

                // Renommer le nouveau fichier avec le nom du candidat et un timestamp
                $newFilename = preg_replace('/\s+/', '_', strtolower($candidat->getNomcomplet())) . '_' . time() . '.' . $cvFile->guessExtension();
                try {
                    $cvFile->move($cvDirectory, $newFilename);
                    // Stocker le chemin relatif dans l'entité
                    $candidat->setCvPath('cv/' . $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du CV.');
                }
            }

            // Gestion du fichier Image
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $imagesDirectory = $this->getParameter('images_directory'); // Par exemple, défini dans parameters.yaml

                // Optionnel : archiver ou supprimer l'ancienne image
                if ($candidat->getImagePersonnelle() && file_exists($this->getParameter('kernel.project_dir') . '/public/' . $candidat->getImagePersonnelle())) {
                    $archiveDir = $imagesDirectory . '/archive';
                    if (!is_dir($archiveDir)) {
                        mkdir($archiveDir, 0777, true);
                    }
                    rename($this->getParameter('kernel.project_dir') . '/public/' . $candidat->getImagePersonnelle(), $archiveDir . '/' . basename($candidat->getImagePersonnelle()));
                }

                // Renommer le nouveau fichier
                $newFilename = preg_replace('/\s+/', '_', strtolower($candidat->getNomcomplet())) . '_' . time() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move($imagesDirectory, $newFilename);
                    $candidat->setImagePersonnelle('images/' . $newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                }
            }

            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
            return $this->redirectToRoute('candidat_profile');
        }

        return $this->render('dashboard/candidat_profile_edit.html.twig', [
            'candidat' => $candidat,
            'form'     => $form->createView(),
        ]);
    }
}
