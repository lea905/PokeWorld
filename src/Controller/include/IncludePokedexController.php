<?php

namespace App\Controller\include;

use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

#[Route('/include/pokedex')]
final class IncludePokedexController extends AbstractController
{

//    #[Route('/listing', name: 'app_include_listing_pokedex', methods: ['GET'])]
//    public function listing(PokemonRepository $pokemonRepository): Response
//    {
//        $pokemons = $pokemonRepository->findAll();
//        return $this->render('pokedex/index.html.twig', [
//            'pokemons' => $pokemons,
//        ]);
//
//    }

}
