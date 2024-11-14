<?php

namespace App\Entity;

use App\Repository\TiersRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TiersRepository::class)]
class Tiers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name:'numTiers', length: 20)]
    private ?string $numTiers = null;

    #[ORM\Column(name:'nomTiers',length: 255)]
    private ?string $nomTiers = null;

    #[ORM\Column(name:'adresseTiers',length: 255)]
    private ?string $adresseTiers = null;

    #[ORM\ManyToOne(inversedBy: 'tiers')]
    #[ORM\JoinColumn(name:'typeTiers_id', nullable: false)]
    private ?TypeTiers $typeTiers = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumTiers(): ?string
    {
        return $this->numTiers;
    }

    public function setNumTiers(string $numTiers): static
    {
        $this->numTiers = $numTiers;

        return $this;
    }

    public function getNomTiers(): ?string
    {
        return $this->nomTiers;
    }

    public function setNomTiers(string $nomTiers): static
    {
        $this->nomTiers = $nomTiers;

        return $this;
    }

    public function getAdresseTiers(): ?string
    {
        return $this->adresseTiers;
    }

    public function setAdresseTiers(string $adresseTiers): static
    {
        $this->adresseTiers = $adresseTiers;

        return $this;
    }

    public function getTypeTiers(): ?TypeTiers
    {
        return $this->typeTiers;
    }

    public function setTypeTiers(?TypeTiers $typeTiers): static
    {
        $this->typeTiers = $typeTiers;

        return $this;
    }
}
