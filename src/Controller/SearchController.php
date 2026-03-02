<?php

namespace App\Controller;

use App\Repository\AreneRepository;
use App\Repository\DresseurRepository;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/recherche', name: 'app_search', methods: ['GET'])]
    public function index(
        Request $request,
        PokemonRepository $pokemonRepository,
        DresseurRepository $dresseurRepository,
        AreneRepository $areneRepository
    ): Response {
        // GET /recherche?q=terme
        $query = $request->query->get('q', '');
        $query = trim($query);

        $pokemons = [];
        $dresseurs = [];
        $arenes = [];

        if (!empty($query)) {
            $pokemons = $pokemonRepository->findBySearchQuery($query);
            $dresseurs = $dresseurRepository->findBySearchQuery($query);
            $arenes = $areneRepository->findBySearchQuery($query);
        }

        return $this->render('search/index.html.twig', [
            'query' => $query,
            'pokemons' => $pokemons,
            'dresseurs' => $dresseurs,
            'arenes' => $arenes,
        ]);
    }
}
