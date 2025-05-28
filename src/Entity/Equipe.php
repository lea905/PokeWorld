<?php

namespace App\Entity;

use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EquipeRepository::class)]
class Equipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $niveau = null;

    #[ORM\ManyToOne(targetEntity: Dresseur::class, inversedBy: 'equipe')]
    #[ORM\JoinColumn(name: "idDresseur", referencedColumnName: "id", nullable: false)]
    private ?Dresseur $dresseur = null;

    #[ORM\ManyToOne(targetEntity: Pokemon::class, inversedBy: 'equipes')]
    #[ORM\JoinColumn(name: "idPokemon", referencedColumnName: "idPokemon", nullable: false)]
    private ?Pokemon $pokemon = null;


    public function getDresseur(): ?Dresseur
    {
        return $this->dresseur;
    }

    public function setDresseur(?Dresseur $dresseur): void
    {
        $this->dresseur = $dresseur;
    }

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): void
    {
        $this->pokemon = $pokemon;
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }
}
