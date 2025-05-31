<?php

namespace App\Entity;

use App\Repository\PokemonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PokemonRepository::class)]
class Pokemon
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_pokemon")]
    private ?int $idPokemon = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $nomJaponais = null;

    #[ORM\Column]
    private ?int $numeroPokedex = null;

    #[ORM\Column]
    private ?int $generation = null;

    #[ORM\ManyToOne(targetEntity: Type::class)]
    #[ORM\JoinColumn(name: "type1", referencedColumnName: "id", nullable: false)]
    private ?Type $type1 = null;


    #[ORM\ManyToOne(targetEntity: Type::class)]
    #[ORM\JoinColumn(name: "type2", referencedColumnName: "id", nullable: true)]
    private ?Type $type2 = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $imagePrincipale = null;
    #[ORM\Column]
    private ?bool $megaEvolutionPossible = null;

    #[ORM\Column]
    private ?bool $dynamaxPossible = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'evolutionsSuivantes')]
    #[ORM\JoinColumn(name: "idEvolutionPrecedente", referencedColumnName: "id_pokemon", nullable: true)]
    private ?Pokemon $evolutionPrecedente = null;

    #[ORM\OneToMany(mappedBy: 'evolutionPrecedente', targetEntity: self::class)]
    private Collection $evolutionsSuivantes;

    #[ORM\OneToMany(mappedBy: 'pokemon', targetEntity: Equipe::class)]
    private Collection $equipes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $crySoundUrl = null;

    #[ORM\Column]
    private ?int $pv = null;

    #[ORM\Column]
    private ?int $attaque = null;

    #[ORM\Column]
    private ?int $defense = null;

    #[ORM\Column]
    private ?int $attaqueSpe = null;

    #[ORM\Column]
    private ?int $defenceSpe = null;

    #[ORM\Column]
    private ?int $vitesse = null;


    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->evolutionsSuivantes = new ArrayCollection();
        $this->equipes = new ArrayCollection();
    }


    public function getEvolutionPrecedente(): ?Pokemon
    {
        return $this->evolutionPrecedente;
    }

    public function setEvolutionPrecedente(?Pokemon $evolutionPrecedente): void
    {
        $this->evolutionPrecedente = $evolutionPrecedente;
    }

    public function getEvolutionsSuivantes(): Collection
    {
        return $this->evolutionsSuivantes;
    }

    public function setEvolutionsSuivantes(Collection $evolutionsSuivantes): void
    {
        $this->evolutionsSuivantes = $evolutionsSuivantes;
    }

    public function getEquipes(): Collection
    {
        return $this->equipes;
    }

    public function setEquipes(Collection $equipes): void
    {
        $this->equipes = $equipes;
    }

    public function getIdPokemon(): ?int
    {
        return $this->idPokemon;
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


    public function setIdPokemon(int $idPokemon): static
    {
        $this->idPokemon = $idPokemon;

        return $this;
    }

    public function getNomJaponais(): ?string
    {
        return $this->nomJaponais;
    }

    public function setNomJaponais(string $nomJaponais): static
    {
        $this->nomJaponais = $nomJaponais;

        return $this;
    }

    public function getNumeroPokedex(): ?int
    {
        return $this->numeroPokedex;
    }

    public function setNumeroPokedex(int $numeroPokedex): static
    {
        $this->numeroPokedex = $numeroPokedex;

        return $this;
    }

    public function getGeneration(): ?int
    {
        return $this->generation;
    }

    public function setGeneration(int $generation): static
    {
        $this->generation = $generation;

        return $this;
    }

    public function getType1(): ?Type
    {
        return $this->type1;
    }

    public function setType1(Type $type1): static
    {
        $this->type1 = $type1;

        return $this;
    }

    public function getType2(): ?Type
    {
        return $this->type2;
    }

    public function setType2(?Type $type2): static
    {
        $this->type2 = $type2;

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

    public function getImagePrincipale(): ?string
    {
        return $this->imagePrincipale;
    }

    public function setImagePrincipale(string $imagePrincipale): static
    {
        $this->imagePrincipale = $imagePrincipale;

        return $this;
    }

    public function isMegaEvolutionPossible(): ?bool
    {
        return $this->megaEvolutionPossible;
    }

    public function setMegaEvolutionPossible(bool $megaEvolutionPossible): static
    {
        $this->megaEvolutionPossible = $megaEvolutionPossible;

        return $this;
    }

    public function isDynamaxPossible(): ?bool
    {
        return $this->dynamaxPossible;
    }

    public function setDynamaxPossible(bool $dynamaxPossible): static
    {
        $this->dynamaxPossible = $dynamaxPossible;

        return $this;
    }

    public function getCrySoundUrl(): ?string
    {
        return $this->crySoundUrl;
    }

    public function setCrySoundUrl(?string $crySoundUrl): static
    {
        $this->crySoundUrl = $crySoundUrl;

        return $this;
    }

    public function getPv(): ?int
    {
        return $this->pv;
    }

    public function setPv(int $pv): static
    {
        $this->pv = $pv;

        return $this;
    }

    public function getAttaque(): ?int
    {
        return $this->attaque;
    }

    public function setAttaque(int $attaque): static
    {
        $this->attaque = $attaque;

        return $this;
    }

    public function getDefense(): ?int
    {
        return $this->defense;
    }

    public function setDefense(int $defense): static
    {
        $this->defense = $defense;

        return $this;
    }

    public function getAttaqueSpe(): ?int
    {
        return $this->attaqueSpe;
    }

    public function setAttaqueSpe(int $attaqueSpe): static
    {
        $this->attaqueSpe = $attaqueSpe;

        return $this;
    }

    public function getDefenceSpe(): ?int
    {
        return $this->defenceSpe;
    }

    public function setDefenceSpe(int $defenceSpe): static
    {
        $this->defenceSpe = $defenceSpe;

        return $this;
    }

    public function getVitesse(): ?int
    {
        return $this->vitesse;
    }

    public function setVitesse(int $vitesse): static
    {
        $this->vitesse = $vitesse;

        return $this;
    }
}
