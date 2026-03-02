<?php

namespace App\Controller;

use App\Entity\Arene;
use App\Repository\AreneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AreneController extends AbstractController
{
    #[Route('/arenes', name: 'app_arene_index')]
    public function index(AreneRepository $areneRepository): Response
    {
        return $this->render('arene/index.html.twig', [
            'arenes' => $areneRepository->findAll(),
        ]);
    }

    #[Route('/arene/{slug}', name: 'app_arene_show')]
    public function show(string $slug, AreneRepository $areneRepository): Response
    {
        $arene = $areneRepository->findOneBy(['slug' => $slug]);

        if (!$arene) {
            throw $this->createNotFoundException('Arène non trouvée');
        }

        return $this->render('arene/show.html.twig', [
            'arene' => $arene,
        ]);
    }
}
