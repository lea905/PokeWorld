<?php

namespace App\Controller;

use App\Entity\Pokemon;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/pokedex')]
final class PokedexController extends AbstractController
{
    #[Route('/{generation?}',name: 'app_pokedex_index', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository, ?int $generation = null): Response
    {
        if ($generation) {
            $pokemons = $pokemonRepository->findBy(['generation' => $generation]);
        } else {
            $pokemons = $pokemonRepository->findAll();
        }

        return $this->render('pokedex/index.html.twig', [
            'pokemons' => $pokemons,
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
