<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardRessourcesController extends AbstractController
{
    #[Route('/dashboard/ressources', name: 'dashboard_ressources')]
    public function index(): Response
    {
        // Liste des ressources
        $resources = [
            [
                'title' => 'Coursera',
                'url' => 'https://www.coursera.org/',
                'domain' => 'programation frontend / backend',
                'description' => 'Accédez à des cours universitaires de prestigieuses institutions dans divers domaines.'
            ],
            [
                'title' => 'edX',
                'url' => 'https://www.edx.org/',
                'domain' => 'Marketing Digital',
                'description' => 'Plateforme offrant des cours de haut niveau dans plusieurs domaines, y compris la technologie et les sciences.'
            ],
            [
                'title' => 'Udemy',
                'url' => 'https://www.udemy.com/',
                'domain' => 'programmation au marketing ...',
                'description' => 'Un vaste catalogue de cours couvrant des sujets variés, de la programmation au marketing.'
            ],
            [
                'title' => 'Khan Academy',
                'url' => 'https://www.khanacademy.org/',
                'domain' => 'mathématoque et sciences',
                'description' => 'Ressources éducatives gratuites pour apprendre les mathématiques, les sciences et plus encore.'
            ],
            [
                'title' => 'Codecademy',
                'url' => 'https://www.codecademy.com/',
                'domain' => 'Programmation',
                'description' => 'Apprenez à coder grâce à des cours interactifs sur différents langages de programmation.'
            ],
            [
                'title' => 'Pluralsight',
                'url' => 'https://www.pluralsight.com/',
                'domain' => 'Technologie',
                'description' => 'Formations techniques et spécialisées pour les professionnels de la tech.'
            ],
            [
                'title' => 'LinkedIn Learning',
                'url' => 'https://www.linkedin.com/learning/',
                'domain' => 'Formation professionnelle ...',
                'description' => 'Accédez à des cours pour améliorer vos compétences professionnelles et techniques.'
            ],
            [
                'title' => 'FutureLearn',
                'url' => 'https://www.futurelearn.com/',
                'domain' => 'Marketing Digital',
                'description' => 'Plateforme de cours en ligne avec une approche collaborative pour apprendre ensemble.'
            ],
            [
                'title' => 'MIT OpenCourseWare',
                'url' => 'https://ocw.mit.edu/',
                'domain' => 'Technologie & Développement Web',
                'description' => 'Accès gratuit aux cours du MIT, couvrant de nombreux sujets en ingénierie, science et technologie.'
            ],
            [
                'title' => 'Harvard Online',
                'url' => 'https://online-learning.harvard.edu/',
                'domain' => 'Cours en ligne',
                'description' => 'Cours en ligne offerts par Harvard pour approfondir vos connaissances dans divers domaines.'
            ],
            [
                'title' => 'Alison',
                'url' => 'https://alison.com/',
                'domain' => 'Data Science & Intelligence Artificielle',
                'description' => 'Des cours gratuits pour développer vos compétences professionnelles et personnelles.'
            ],
            [
                'title' => 'Skillshare',
                'url' => 'https://www.skillshare.com/',
                'domain' => 'Créativité & Technologie',
                'description' => 'Plateforme d’apprentissage basée sur la communauté avec des cours en design, photographie et plus.'
            ],
            [
                'title' => 'Udacity',
                'url' => 'https://www.udacity.com/',
                'domain' => 'Technologie',
                'description' => 'Cours et nanodegrees orientés vers les compétences technologiques et la programmation.'
            ],
            [
                'title' => 'Code.org',
                'url' => 'https://code.org/',
                'domain' => 'Programmation',
                'description' => 'Initiative pour apprendre la programmation dès le plus jeune âge grâce à des cours interactifs.'
            ],
            [
                'title' => 'TeamTreehouse',
                'url' => 'https://teamtreehouse.com/',
                'domain' => 'Programmation & Design',
                'description' => 'Cours interactifs pour apprendre à coder et à concevoir des sites web modernes.'
            ],
            [
                'title' => 'OpenClassrooms',
                'url' => 'https://openclassrooms.com/',
                'domain' => 'Formation professionnelle',
                'description' => 'Cours en ligne pour développer des compétences professionnelles dans divers domaines, notamment la tech.'
            ],
            [
                'title' => 'Google Digital Garage',
                'url' => 'https://learndigital.withgoogle.com/digitalgarage',
                'domain' => 'Marketing digital',
                'description' => 'Cours gratuits pour améliorer vos compétences en marketing digital et en développement web.'
            ],
            [
                'title' => 'IBM Skills Network',
                'url' => 'https://www.ibm.com/skills/',
                'domain' => 'Technologie & Innovation',
                'description' => 'Formations pour développer vos compétences en informatique, cloud, et data science.'
            ],
            [
                'title' => 'FutureLearn Business',
                'url' => 'https://www.futurelearn.com/courses/business',
                'domain' => 'Business & Management',
                'description' => 'Cours axés sur le management et le développement commercial pour booster votre carrière.'
            ],
            [
                'title' => 'edureka!',
                'url' => 'https://www.edureka.co/',
                'domain' => 'Technologie & IT',
                'description' => 'Formations en ligne pour les professionnels de l\'informatique, allant du développement à la cybersécurité.'
            ],
        ];

        return $this->render('dashboard/ressources.html.twig', [
            'resources' => $resources,
        ]);
    }
}
