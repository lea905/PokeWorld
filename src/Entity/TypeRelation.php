<?php

namespace App\Entity;

use App\Repository\TypeRelationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRelationRepository::class)]
class TypeRelation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'relationsEnAttaque')]
    #[ORM\JoinColumn(name: "idTypeAttaquant", referencedColumnName: "id", nullable: false)]
    private ?Type $typeAttaquant = null;

    #[ORM\ManyToOne(targetEntity: Type::class, inversedBy: 'relationsEnDefense')]
    #[ORM\JoinColumn(name: "idTypeDefenseur", referencedColumnName: "id", nullable: false)]
    private ?Type $typeDefenseur = null;

    #[ORM\Column]
    private ?float $efficacite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAttaquant(): ?Type
    {
        return $this->typeAttaquant;
    }

    public function setTypeAttaquant(?Type $typeAttaquant): void
    {
        $this->typeAttaquant = $typeAttaquant;
    }

    public function getTypeDefenseur(): ?Type
    {
        return $this->typeDefenseur;
    }

    public function setTypeDefenseur(?Type $typeDefenseur): void
    {
        $this->typeDefenseur = $typeDefenseur;
    }

    public function getEfficacite(): ?float
    {
        return $this->efficacite;
    }

    public function setEfficacite(float $efficacite): static
    {
        $this->efficacite = $efficacite;

        return $this;
    }
}
