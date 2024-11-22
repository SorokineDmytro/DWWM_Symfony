<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
#[ORM\Table(name: 'produit')]

class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name:'numProduit', length: 20)]
    private ?string $numProduit = null;

    #[ORM\Column(length: 255)]
    private ?string $designation = null;

    #[ORM\Column(name:'prixUnitaire', type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $prixUnitaire = null;

    #[ORM\Column(name:'prixRevient', type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $prixRevient = null;

    #[ORM\ManyToOne(inversedBy: 'produits')]
    private ?Categorie $categorie = null;

    /**
     * @var Collection<int, LigneMouvement>
     */
    #[ORM\OneToMany(targetEntity: LigneMouvement::class, mappedBy: 'produit')]
    private Collection $ligneMouvements;

    public function __construct()
    {
        $this->ligneMouvements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumProduit(): ?string
    {
        return $this->numProduit;
    }

    public function setNumProduit(string $numProduit): static
    {
        $this->numProduit = $numProduit;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): static
    {
        $this->designation = $designation;

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

    public function getPrixRevient(): ?string
    {
        return $this->prixRevient;
    }

    public function setPrixRevient(?string $prixRevient): static
    {
        $this->prixRevient = $prixRevient;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, LigneMouvement>
     */
    public function getLigneMouvements(): Collection
    {
        return $this->ligneMouvements;
    }

    public function addLigneMouvement(LigneMouvement $ligneMouvement): static
    {
        if (!$this->ligneMouvements->contains($ligneMouvement)) {
            $this->ligneMouvements->add($ligneMouvement);
            $ligneMouvement->setProduit($this);
        }

        return $this;
    }

    public function removeLigneMouvement(LigneMouvement $ligneMouvement): static
    {
        if ($this->ligneMouvements->removeElement($ligneMouvement)) {
            // set the owning side to null (unless already changed)
            if ($ligneMouvement->getProduit() === $this) {
                $ligneMouvement->setProduit(null);
            }
        }

        return $this;
    }
}
