<?php

namespace App\Entity;

use App\Repository\AreneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: AreneRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Arene
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $badge = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageBadge = null;

    #[ORM\OneToOne(targetEntity: Dresseur::class, inversedBy: 'arene')]
    #[ORM\JoinColumn(name: "idChampion", referencedColumnName: "id", nullable: true, onDelete: "SET NULL")]
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): static
    {
        $this->slug = $slug;
        return $this;
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

    public function setBadge(?string $badge): static
    {
        $this->badge = $badge;

        return $this;
    }

    public function getImageBadge(): ?string
    {
        return $this->imageBadge;
    }

    public function setImageBadge(?string $imageBadge): static
    {
        $this->imageBadge = $imageBadge;

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function computeSlug(): void
    {
        if (empty($this->slug)) {
            $slugger = new AsciiSlugger('fr');
            // Arene names should be unique enough, e.g. "Argenta", "Azuria"
            $this->slug = strtolower($slugger->slug(trim($this->nom))->toString());
        }
    }
}
