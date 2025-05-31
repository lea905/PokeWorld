<?php

namespace App\Controller;
use App\Repository\DresseurRepository;
use App\Repository\PokemonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home_index', methods: ['GET'])]
    public function home(PokemonRepository $pokemonRepository, DresseurRepository $dresseurRepository): Response
    {
        $pokemons = array_slice($pokemonRepository->findAll(), 0, 6);
        $dresseurs = array_slice($dresseurRepository->findAll(), 0, 6);

        return $this->render('index.html.twig',[
            'pokemons' => $pokemons,
            'dresseurs' => $dresseurs
        ]);
    }
}