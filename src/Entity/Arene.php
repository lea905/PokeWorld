<?php

namespace App\Entity;

use App\Repository\AreneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AreneRepository::class)]
class Arene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255)]
    private ?string $badge = null;

    #[ORM\Column(length: 255)]
    private ?string $imageBadge = null;

    #[ORM\OneToOne(targetEntity: Dresseur::class, inversedBy: 'arene')]
    #[ORM\JoinColumn(name: "idChampion", referencedColumnName: "id", nullable: false)]
    private ?Dresseur $champion = null;

    public function getChampion(): ?Dresseur
    {
        return $this->champion;
    }

    public function setChampion(?Dresseur $champion): Arene
    {
        $this->champion = $champion;
        return $this;
    }


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

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getBadge(): ?string
    {
        return $this->badge;
    }

    public function setBadge(string $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function getImageBadge(): ?string
    {
        return $this->imageBadge;
    }

    public function setImageBadge(string $imageBadge): static
    {
        $this->imageBadge = $imageBadge;

        return $this;
    }
}
