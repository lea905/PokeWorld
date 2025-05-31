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

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;



    #[ORM\OneToMany(mappedBy: 'dresseur', targetEntity: Equipe::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $equipe;

    #[ORM\OneToOne(mappedBy: 'champion', targetEntity: Arene::class)]
    private ?Arene $arene = null;

    public function __construct()
    {
        $this->equipe = new ArrayCollection();
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

    /**
     * @return Collection<int, Equipe>
     */
    public function getEquipe(): Collection
    {
        return $this->equipe;
    }

    public function addEquipe(Equipe $equipe): static
    {
        if (!$this->equipe->contains($equipe)) {
            $this->equipe->add($equipe);
            $equipe->setDresseur($this);
        }
        return $this;
    }

    public function removeEquipe(Equipe $equipe): static
    {
        if ($this->equipe->removeElement($equipe)) {
            // set the owning side to null (unless already changed)
            if ($equipe->getDresseur() === $this) {
                $equipe->setDresseur(null);
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
