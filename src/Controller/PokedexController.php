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
    #[Route(name: 'app_pokedex_index', methods: ['GET'])]
    public function index(PokemonRepository $pokemonRepository): Response
    {
        return $this->render('pokedex/index.html.twig', [
            'pokemons' => $pokemonRepository->findAll(),
        ]);
    }

    #[Route('/{nom}', name: 'app_pokedex_show', methods: ['GET'])]
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
