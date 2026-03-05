<?php

namespace App\Entity;

use App\Repository\OrganisationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[ORM\Entity(repositoryClass: OrganisationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Organisation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $but = null;

    /**
     * @var Collection<int, Dresseur>
     */
    #[ORM\OneToMany(targetEntity: Dresseur::class, mappedBy: 'organisation')]
    private Collection $dresseurs;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    private ?string $slug = null;

    public function __construct()
    {
        $this->dresseurs = new ArrayCollection();
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

    public function getBut(): ?string
    {
        return $this->but;
    }

    public function setBut(string $but): static
    {
        $this->but = $but;

        return $this;
    }

    /**
     * @return Collection<int, Dresseur>
     */
    public function getDresseurs(): Collection
    {
        return $this->dresseurs;
    }

    public function addDresseur(Dresseur $dresseur): static
    {
        if (!$this->dresseurs->contains($dresseur)) {
            $this->dresseurs->add($dresseur);
            $dresseur->setOrganisation($this);
        }

        return $this;
    }

    public function removeDresseur(Dresseur $dresseur): static
    {
        if ($this->dresseurs->removeElement($dresseur)) {
            // set the owning side to null (unless already changed)
            if ($dresseur->getOrganisation() === $this) {
                $dresseur->setOrganisation(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->nom;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
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

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function computeSlug(): void
    {
        if (empty($this->slug)) {
            $slugger = new AsciiSlugger('fr');
            $fullName = $this->nom;
            $this->slug = strtolower($slugger->slug(trim($fullName))->toString());
        }
    }
}
