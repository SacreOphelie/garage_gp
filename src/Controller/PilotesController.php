<?php

namespace App\Controller;

use App\Entity\Pilote;
use App\Form\PiloteType;
use App\Repository\PiloteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class PilotesController extends AbstractController
{
    /**
     * Permet d'afficher tout les pilotes
     *
     * @return Response
     */
    #[Route('/pilotes', name: 'all_pilotes')]
    public function index(PiloteRepository $repo): Response
    {
        $pilotes = $repo->findAll();
        return $this->render('pilotes/index.html.twig', [
            'pilotes' => $pilotes,
            
        ]);
    }

    /**
     * le formulaire d'ajout d'un pilote
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/pilotes/new', name: 'pilote_new')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $pilote = new Pilote();

        $form = $this->createForm(PiloteType::class, $pilote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($pilote);
            $em->flush();

            return $this->redirectToRoute('all_pilotes');
        }

        return $this->render('pilotes/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Permet d'afficher la page du pilote
     * Attention {slug} c'est paramConverter pas lié à Symfony Flex
     * @param string $slug
     * @return Response
     */
    #[Route('/pilotes/{slug}', name: 'pilote_show')]
    public function show(string $slug, PiloteRepository $repo): Response
    {
        $pilote = $repo->findOneBy(['slug' => $slug]);

        if (!$pilote) {
            throw $this->createNotFoundException('Pilote non trouvé');
        }

        $sameTeam = $repo->findBy([
            'ecurie' => $pilote->getEcurie()
        ]);

        return $this->render("pilotes/show.html.twig",[
            "pilote" => $pilote,
            "sameTeam" => $sameTeam
        ]);
    }
}
