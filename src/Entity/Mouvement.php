<?php

namespace App\Entity;

use App\Repository\MouvementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MouvementRepository::class)]
class Mouvement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'numMouvement', length: 20)]
    private ?string $numMouvement = null;

    #[ORM\Column(name: 'dateMouvement',type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dateMouvement = null;

    #[ORM\ManyToOne(inversedBy: 'mouvement')]
    private ?TypeMouvement $typeMouvement = null;

    #[ORM\ManyToOne(inversedBy: 'tiers')]
    private ?Tiers $tiers = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumMouvement(): ?string
    {
        return $this->numMouvement;
    }

    public function setNumMouvement(string $numMouvement): static
    {
        $this->numMouvement = $numMouvement;

        return $this;
    }

    public function getDateMouvement(): ?\DateTimeInterface
    {
        return $this->dateMouvement;
    }

    public function setDateMouvement(?\DateTimeInterface $dateMouvement): static
    {
        $this->dateMouvement = $dateMouvement;

        return $this;
    }

    public function getTypeMouvement(): ?TypeMouvement
    {
        return $this->typeMouvement;
    }

    public function setTypeMouvement(?TypeMouvement $typeMouvement): static
    {
        $this->typeMouvement = $typeMouvement;

        return $this;
    }

    public function getTiers(): ?Tiers
    {
        return $this->tiers;
    }

    public function setTiers(?Tiers $tiers): static
    {
        $this->tiers = $tiers;

        return $this;
    }
}
