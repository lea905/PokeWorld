<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\OneToMany(mappedBy: 'typeAttaquant', targetEntity: TypeRelation::class)]
    private Collection $relationsEnAttaque;

    #[ORM\OneToMany(mappedBy: 'typeDefenseur', targetEntity: TypeRelation::class)]
    private Collection $relationsEnDefense;

    public function __construct()
    {
        $this->relationsEnAttaque = new ArrayCollection();
        $this->relationsEnDefense = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRelationsEnAttaque(): Collection
    {
        return $this->relationsEnAttaque;
    }

    public function setRelationsEnAttaque(Collection $relationsEnAttaque): void
    {
        $this->relationsEnAttaque = $relationsEnAttaque;
    }

    public function getRelationsEnDefense(): Collection
    {
        return $this->relationsEnDefense;
    }

    public function setRelationsEnDefense(Collection $relationsEnDefense): void
    {
        $this->relationsEnDefense = $relationsEnDefense;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
}
