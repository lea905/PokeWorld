<?php

namespace App\Controller;

use App\Entity\Organisation;
use App\Repository\OrganisationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class OrganisationController extends AbstractController
{
    #[Route('/organisations', name: 'app_organisation_index')]
    public function index(OrganisationRepository $organisationRepository): Response
    {

        return $this->render('organisation/index.html.twig', [
            'organisations' => $organisationRepository->findAll(),
        ]);
    }

    #[Route('/organisation/{slug}', name: 'app_organisation_show')]
    public function show(string $slug, OrganisationRepository $organisationRepository): Response
    {
        $organisation = $organisationRepository->findOneBy(['slug' => $slug]);

        if (!$organisation){
            throw $this->createNotFoundException('Organisation not found');
        }

        return $this->render('organisation/show.html.twig', [
            'organisation' => $organisation,
        ]);
    }

}