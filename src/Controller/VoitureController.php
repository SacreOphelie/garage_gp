<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Voiture;
//use Doctrine\Persistence\ManagerRegistry;
use App\Form\VoitureType;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class VoitureController extends AbstractController
{
    /**
     * Permet d'afficher les annonces
     * @param AllRepository $repo
     * @return Response
     */
    #[Route('/voitures', name: 'all_voitures')]
    public function index(VoitureRepository $repo): Response
    {
        // appel au model
        $voitures = $repo->findAll();

        // vue
        return $this->render('voitures/index.html.twig', [
            'voitures' => $voitures
        ]);
    }

    /**
     * le formulaire d'ajout d'une voiture
     *
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/voitures/new', name: 'voiture_new')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class,$voiture);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // gestion des images
            foreach($voiture->getImages() as $image)
            {
                $image->setVoiture($voiture);
                $em->persist($image);
            }

            $em->persist($voiture);
            $em->flush();
            $this->addFlash(
                'success',
                "L'annonce <strong>".$voiture->getTitre()."</strong> a bien été enregistrée"
            );
            return $this->redirectToRoute('voiture_show',['slug'=>$voiture->getSlug()]);
        }

        return $this->render('voitures/new.html.twig',[
            'myForm' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher la page de l'annonce choisie par l'utilisateur avec son slug
     * Attention {slug} c'est paramConverter pas lié à Symfony Flex
     * @param string $slug
     * @return Response
     */
    #[Route('/voitures/{slug}', name: 'voiture_show')]
    public function show(string $slug, VoitureRepository $repo): Response
    {
        $voiture = $repo->findOneBy(['slug' => $slug]);

        if (!$voiture) {
            throw $this->createNotFoundException('Voiture non trouvée.');
        }

        return $this->render("voitures/show.html.twig",[
            "voiture" => $voiture
        ]);
    }




}