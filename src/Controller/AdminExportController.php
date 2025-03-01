<?php

namespace App\Controller;

use App\Entity\Candidat;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;

class AdminExportController extends AbstractController
{
    #[Route('/admin/candidates/export', name: 'admin_candidates_export')]
    public function exportCandidates(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les candidats
        $candidates = $entityManager->getRepository(Candidat::class)->findAll();

        // Création du spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir les entêtes de colonnes
        $sheet->setCellValue('A1', 'Nom Complet');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Sexe');
        $sheet->setCellValue('D1', 'Ville');
        $sheet->setCellValue('E1', 'Date d\'inscription');

        $rowNumber = 2;
        foreach ($candidates as $candidate) {
            $sheet->setCellValue('A' . $rowNumber, $candidate->getNomcomplet());
            $sheet->setCellValue('B' . $rowNumber, $candidate->getUser()->getEmail());
            $sheet->setCellValue('C' . $rowNumber, $candidate->getSexe());
            $sheet->setCellValue('D' . $rowNumber, $candidate->getVille());
            // On suppose que l'entité Users possède un champ date_inscription
            $dateInscription = $candidate->getUser()->getDateInscription();
            $sheet->setCellValue('E' . $rowNumber, $dateInscription ? $dateInscription->format('d/m/Y') : '');
            $rowNumber++;
        }

        // Création du writer Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'candidates_export.xlsx';

        // Créer une réponse avec les headers appropriés pour le téléchargement
        $response = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $disposition);

        // Écrire le contenu dans un buffer et le renvoyer dans la réponse
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        $response->setContent($content);

        return $response;
    }
    #[Route('/admin/enterprises/export', name: 'admin_enterprises_export')]
    public function exportEnterprises(EntityManagerInterface $entityManager): Response
    {
        // Récupérer toutes les entreprises
        $enterprises = $entityManager->getRepository(Entreprise::class)->findAll();

        // Création du Spreadsheet
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Définir les entêtes de colonnes
        $sheet->setCellValue('A1', 'Nom de l\'Entreprise');
        $sheet->setCellValue('B1', 'Email');
        $sheet->setCellValue('C1', 'Secteur');
        $sheet->setCellValue('D1', 'Date d\'inscription');

        $rowNumber = 2;
        foreach ($enterprises as $enterprise) {
            $sheet->setCellValue('A' . $rowNumber, $enterprise->getNomEntreprise());
            $sheet->setCellValue('B' . $rowNumber, $enterprise->getUser()->getEmail());
            $sheet->setCellValue('C' . $rowNumber, $enterprise->getSecteurs() ?? 'Non renseigné');
            // Supposons que l'entité Users a un champ "date_inscription"
            $dateInscription = $enterprise->getUser()->getDateInscription();
            $sheet->setCellValue('D' . $rowNumber, $dateInscription ? $dateInscription->format('d/m/Y') : '');
            $rowNumber++;
        }

        // Création du writer Excel
        $writer = new Xlsx($spreadsheet);
        $filename = 'enterprises_export.xlsx';

        // Créer une réponse avec les headers appropriés pour le téléchargement
        $response = new Response();
        $disposition = $response->headers->makeDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, $filename);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', $disposition);

        // Écriture dans le buffer
        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();
        $response->setContent($content);

        return $response;
    }
}
