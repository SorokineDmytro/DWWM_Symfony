<?php

namespace App\Entity;

use App\Repository\MouvementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    /**
     * @var Collection<int, LigneMouvement>
     */
    #[ORM\OneToMany(targetEntity: LigneMouvement::class, mappedBy: 'mouvement')]
    private Collection $ligneMouvements;

    public function __construct()
    {
        $this->ligneMouvements = new ArrayCollection();
    }

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
            $ligneMouvement->setMouvement($this);
        }

        return $this;
    }

    public function removeLigneMouvement(LigneMouvement $ligneMouvement): static
    {
        if ($this->ligneMouvements->removeElement($ligneMouvement)) {
            // set the owning side to null (unless already changed)
            if ($ligneMouvement->getMouvement() === $this) {
                $ligneMouvement->setMouvement(null);
            }
        }

        return $this;
    }

    public function getTotal() {
        $ligneMouvements = $this->getLigneMouvements();
        $total = 0;
        foreach($ligneMouvements as $ligneMouvement) {
            $quantite = $ligneMouvement->getQuantite();
            $prixUnitaire = $ligneMouvement->getPrixUnitaire();
            $montant = $quantite * $prixUnitaire;
            $total += $montant;
        }
        return $total;
    }
}
