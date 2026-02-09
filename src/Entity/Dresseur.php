<?php

namespace App\Entity;

use App\Repository\DresseurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DresseurRepository::class)]
class Dresseur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $villeNatale = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $ambition = null;

    #[ORM\Column(type: \Doctrine\DBAL\Types\Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;



    #[ORM\OneToMany(mappedBy: 'dresseur', targetEntity: Team::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $teams;

    #[ORM\OneToOne(mappedBy: 'champion', targetEntity: Arene::class)]
    private ?Arene $arene = null;

    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
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

    public function getVilleNatale(): ?string
    {
        return $this->villeNatale;
    }

    public function setVilleNatale(string $villeNatale): static
    {
        $this->villeNatale = $villeNatale;
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

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
            $team->setDresseur($this);
        }
        return $this;
    }

    public function removeTeam(Team $team): static
    {
        if ($this->teams->removeElement($team)) {
            // set the owning side to null (unless already changed)
            if ($team->getDresseur() === $this) {
                $team->setDresseur(null);
            }
        }
        return $this;
    }

    public function getArene(): ?Arene
    {
        return $this->arene;
    }

    public function setArene(?Arene $arene): static
    {
        $this->arene = $arene;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }
}
