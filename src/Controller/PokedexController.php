<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokedex')]
final class PokedexController extends AbstractController
{
    #[Route('/{generation?}', name: 'app_pokedex_index', methods: ['GET'])]
    public function index(Request $request, PokemonRepository $pokemonRepository, TypeRepository $typeRepository, ?int $generation = null): Response
    {
        $type = $request->query->get('type');
        $isLegendary = $request->query->getBoolean('is_legendary');
        $isMythical = $request->query->getBoolean('is_mythical');
        $evolutionStage = $request->query->get('evolution_stage');

        if ($type || $generation || $isLegendary || $isMythical || $evolutionStage) {
            $filters = [];
            if ($type)
                $filters['type'] = $type;
            if ($generation)
                $filters['generation'] = $generation;
            if ($isLegendary)
                $filters['is_legendary'] = true;
            if ($isMythical)
                $filters['is_mythical'] = true;
            if ($evolutionStage)
                $filters['evolution_stage'] = $evolutionStage;

            $pokemons = $pokemonRepository->findByFilters($filters);
        } else {
            $pokemons = $pokemonRepository->findBy([], ['generation' => 'ASC', 'numeroPokedex' => 'ASC']);
        }

        $allTypes = $typeRepository->findValidTypes();

        return $this->render('pokedex/index.html.twig', [
            'pokemons' => $pokemons,
            'allTypes' => $allTypes,
            'currentType' => $type,
            'currentGeneration' => $generation,
            'isLegendary' => $isLegendary,
            'isMythical' => $isMythical,
            'evolutionStage' => $evolutionStage,
        ]);
    }

    #[Route('/show/{nom}', name: 'app_pokedex_show', methods: ['GET'])]
    public function show(string $nom, PokemonRepository $pokemonRepository): Response
    {
        $pokemon = $pokemonRepository->findOneBy(['nom' => $nom]);

        if (!$pokemon) {
            throw $this->createNotFoundException('Ce Pokémon n\'existe pas.');
        }

        return $this->render('pokedex/show.html.twig', [
            'pokemon' => $pokemon,
        ]);
    }
}
