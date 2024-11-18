<?php

namespace App\Entity;

use App\Repository\TypeMouvementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeMouvementRepository::class)]
#[ORM\Table(name:'typeMouvement')]
class TypeMouvement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $prefixe = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(name:'numeroInitial')]
    private ?int $numeroInitial = null;

    #[ORM\Column(length: 10)]
    private ?string $format = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefixe(): ?string
    {
        return $this->prefixe;
    }

    public function setPrefixe(string $prefixe): static
    {
        $this->prefixe = $prefixe;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getNumeroInitial(): ?int
    {
        return $this->numeroInitial;
    }

    public function setNumeroInitial(int $numeroInitial): static
    {
        $this->numeroInitial = $numeroInitial;

        return $this;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }
}
