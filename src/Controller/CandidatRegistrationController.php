<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Candidat;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CandidatRegistrationController extends AbstractController
{
    #[Route('/inscription/candidat', name: 'inscription_candidat', methods: ['GET', 'POST'])]
    public function inscriptionCandidat(Request $request, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            // Récupération des champs du formulaire
            $email         = $request->request->get('email');
            $password      = $request->request->get('password');
            $nomcomplet    = $request->request->get('nomcomplet');
            $dateNaissance = $request->request->get('dateNaissance'); // format attendu : YYYY-MM-DD
            $cnie          = $request->request->get('cnie');
            $sexe          = $request->request->get('sexe');
            $ville         = $request->request->get('ville');
            $competences   = $request->request->get('competences');
            $experiences   = $request->request->get('experiences');
            $formations    = $request->request->get('formations');

            // Création d'un nouvel utilisateur avec rôle candidat
            $user = new Users();
            $user->setEmail($email);
            $user->setPassword($password); // en clair, tel que saisi
            $user->setRole('candidat');
            $entityManager->persist($user);
            $entityManager->flush(); // nécessaire pour générer l'ID de l'utilisateur

            // Création de l'entité candidat
            $candidat = new Candidat();
            $candidat->setUser($user);
            $candidat->setNomcomplet($nomcomplet);
            $candidat->setDateNaissance(new \DateTime($dateNaissance));
            $candidat->setCnie($cnie);
            $candidat->setSexe($sexe);
            $candidat->setVille($ville);
            $candidat->setCompetences($competences);
            $candidat->setExperiences($experiences);
            $candidat->setFormations($formations);

            // Gestion du téléchargement du CV
            /** @var UploadedFile $cvFile */
            $cvFile = $request->files->get('cv');
            if ($cvFile) {
                // On remplace les espaces dans le nom complet par des underscores pour le nom du fichier
                $cvFileName = preg_replace('/\s+/', '_', $nomcomplet) . '_cv.' . $cvFile->guessExtension();
                $cvDirectory = $this->getParameter('cv_directory');
                try {
                    $cvFile->move($cvDirectory, $cvFileName);
                    // Enregistre uniquement le chemin relatif dans la BDD (par exemple "cv/nom_du_fichier.ext")
                    $candidat->setCvPath('cv/' . $cvFileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement du CV.');
                }
            }

            // Gestion du téléchargement de l'image personnelle
            /** @var UploadedFile $imageFile */
            $imageFile = $request->files->get('image_personnelle');
            if ($imageFile) {
                $imageFileName = preg_replace('/\s+/', '_', $nomcomplet) . '_img.' . $imageFile->guessExtension();
                $imagesDirectory = $this->getParameter('images_directory');
                try {
                    $imageFile->move($imagesDirectory, $imageFileName);
                    $candidat->setImagePersonnelle('images/' . $imageFileName);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Erreur lors du téléchargement de l\'image.');
                }
            }

            $entityManager->persist($candidat);
            $entityManager->flush();

            // Message de succès et redirection vers la page de connexion (ou autre)
            $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
            return $this->redirectToRoute('app_login');
        }

        // Affichage du formulaire d'inscription candidat
        return $this->render('auth/inscription_candidat.html.twig');
    }
}
