<?php

namespace App\Controller;

use App\Repository\PiloteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\VoitureRepository;

final class HomeController extends AbstractController
{
    /**
     * Permet de faire la direction vers l'accueil
     *
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function index(): Response
    {
        return $this->render('index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    /**
     * Permet d'afficher les 3 dernières voitures et les 3 derniers pilotes
     *
     * @param VoitureRepository $voitureRepository
     * @return Response
     */
    #[Route('/', name: 'accueil')]
    public function recent(VoitureRepository $voitureRepository, PiloteRepository $piloteRepository): Response
    {
         // On récupère les 3 dernières voitures et 3 derniers pilotes
        $voitures = $voitureRepository->findBy([], ['id' => 'DESC'], 3);
        $pilotes = $piloteRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('index.html.twig', [
            'voitures' => $voitures,
            'pilotes' => $pilotes,
        ]);
    }
}
