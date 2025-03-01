<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        // Invalide la session
        $request->getSession()->invalidate();

        // Redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }
}
