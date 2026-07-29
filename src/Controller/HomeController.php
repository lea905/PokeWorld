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
        $pokemonsCount = $pokemonRepository->count([]);
        $arenesCount = $areneRepository->count([]);

        // Récupérer un seul Pokémon et une seule Arène au hasard
        $pokemonDuJour = $pokemonsCount > 0 ? $pokemonRepository->findBy([], null, 1, rand(0, $pokemonsCount - 1))[0] : null;
        $areneDuJour = $arenesCount > 0 ? $areneRepository->findBy([], null, 1, rand(0, $arenesCount - 1))[0] : null;

        // Récupérer les 3 derniers (sans tout charger en mémoire)
        $derniersPokemons = $pokemonRepository->findBy([], ['idPokemon' => 'DESC'], 3);
        $derniersDresseurs = $dresseurRepository->findBy([], ['id' => 'DESC'], 3);

        return $this->render('index.html.twig', [
            'pokemonDuJour' => $pokemonDuJour,
            'areneDuJour' => $areneDuJour,
            'pokemons' => $derniersPokemons,
            'dresseurs' => $derniersDresseurs,
        ]);
    }
}