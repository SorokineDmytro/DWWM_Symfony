<?php

namespace App\Entity;

use App\Repository\LigneMouvementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LigneMouvementRepository::class)]
#[ORM\Table(name:'ligneMouvement')]
class LigneMouvement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ligneMouvements')]
    private ?Mouvement $mouvement = null;

    #[ORM\ManyToOne(inversedBy: 'ligneMouvements')]
    private ?Produit $produit = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $quantite = null;

    #[ORM\Column(name:'prixUnitaire', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixUnitaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMouvement(): ?Mouvement
    {
        return $this->mouvement;
    }

    public function setMouvement(?Mouvement $mouvement): static
    {
        $this->mouvement = $mouvement;

        return $this;
    }

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }

    public function getQuantite(): ?string
    {
        return $this->quantite;
    }

    public function setQuantite(string $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getPrixUnitaire(): ?string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): static
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }
}
