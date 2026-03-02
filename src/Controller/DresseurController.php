<?php

namespace App\Controller;

use App\Entity\Dresseur;
use App\Repository\DresseurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DresseurController extends AbstractController
{
    #[Route('/dresseurs', name: 'app_dresseur_index')]
    public function index(DresseurRepository $dresseurRepository): Response
    {
        return $this->render('dresseur/index.html.twig', [
            'dresseurs' => $dresseurRepository->findAll(),
        ]);
    }

    #[Route('/dresseur/{slug}', name: 'app_dresseur_show')]
    public function show(string $slug, DresseurRepository $dresseurRepository): Response
    {
        $dresseur = $dresseurRepository->findOneBy(['slug' => $slug]);

        if (!$dresseur) {
            throw $this->createNotFoundException('Dresseur non trouvé');
        }

        return $this->render('dresseur/show.html.twig', [
            'dresseur' => $dresseur,
        ]);
    }
}
