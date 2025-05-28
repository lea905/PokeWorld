<?php

namespace App\Entity;

use App\Repository\DresseurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DresseurRepository::class)]
class Dresseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $lieuOrigine = null;

    #[ORM\Column(length: 255)]
    private ?string $ambition = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'dresseur', targetEntity: Equipe::class)]
    private Collection $equipe;

    public function __construct()
    {
        $this->equipe = new ArrayCollection();
    }


    public function getEquipe(): Collection
    {
        return $this->equipe;
    }

    public function setEquipe(Collection $equipe): void
    {
        $this->equipe = $equipe;
    }

    public function getArene(): ?Arene
    {
        return $this->arene;
    }

    public function setArene(?Arene $arene): void
    {
        $this->arene = $arene;
    }

    #[ORM\OneToOne(mappedBy: 'champion', targetEntity: Arene::class)]
    private ?Arene $arene = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLieuOrigine(): ?string
    {
        return $this->lieuOrigine;
    }

    public function setLieuOrigine(string $lieuOrigine): static
    {
        $this->lieuOrigine = $lieuOrigine;

        return $this;
    }

    public function getAmbition(): ?string
    {
        return $this->ambition;
    }

    public function setAmbition(string $ambition): static
    {
        $this->ambition = $ambition;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
