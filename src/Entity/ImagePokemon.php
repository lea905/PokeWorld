<?php

namespace App\Entity;

use App\Repository\ImagePokemonRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImagePokemonRepository::class)]
class ImagePokemon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $idPokemon = null;

    public function getPokemon(): ?Pokemon
    {
        return $this->pokemon;
    }

    public function setPokemon(?Pokemon $pokemon): void
    {
        $this->pokemon = $pokemon;
    }

    #[ORM\ManyToOne(targetEntity: Pokemon::class, inversedBy: 'images')]
    #[ORM\JoinColumn(name: "idPokemon", referencedColumnName: "id_pokemon", nullable: false)]
    private ?Pokemon $pokemon = null;

    #[ORM\Column(length: 255)]
    private ?string $typeImage = null;

    #[ORM\Column(length: 255)]
    private ?string $urlImage = null;


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getIdPokemon(): ?int
    {
        return $this->idPokemon;
    }

    public function setIdPokemon(int $idPokemon): static
    {
        $this->idPokemon = $idPokemon;

        return $this;
    }

    public function getTypeImage(): ?string
    {
        return $this->typeImage;
    }

    public function setTypeImage(string $typeImage): static
    {
        $this->typeImage = $typeImage;

        return $this;
    }

    public function getUrlImage(): ?string
    {
        return $this->urlImage;
    }

    public function setUrlImage(string $urlImage): static
    {
        $this->urlImage = $urlImage;

        return $this;
    }
}
