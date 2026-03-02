<?php

namespace App\Controller;
use App\Repository\AreneRepository;
use App\Repository\DresseurRepository;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function home(
        PokemonRepository $pokemonRepository,
        DresseurRepository $dresseurRepository,
        AreneRepository $areneRepository
    ): Response {
        $allPokemons = $pokemonRepository->findAll();
        $allDresseurs = $dresseurRepository->findAll();
        $allArenes = $areneRepository->findAll();

        $pokemonDuJour = !empty($allPokemons) ? $allPokemons[array_rand($allPokemons)] : null;
        $areneDuJour = !empty($allArenes) ? $allArenes[array_rand($allArenes)] : null;

        return $this->render('index.html.twig', [
            'pokemonDuJour' => $pokemonDuJour,
            'areneDuJour' => $areneDuJour,
            'pokemons' => array_reverse(array_slice($allPokemons, -3)),
            'dresseurs' => array_reverse(array_slice($allDresseurs, -3)),
        ]);
    }
}