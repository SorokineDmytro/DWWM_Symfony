<?php

namespace App\Entity;

use App\Repository\TypeTiersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeTiersRepository::class)]
#[ORM\Table(name:'typeTiers')]
class TypeTiers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $prefixe = null;

    #[ORM\Column(name:'numeroInitial')]
    private ?int $numeroInitial = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\Column(length: 20)]
    private ?string $format = null;

    /**
     * @var Collection<int, Tiers>
     */
    #[ORM\OneToMany(targetEntity: Tiers::class, mappedBy: 'typeTiers')]
    private Collection $tiers;

    public function __construct()
    {
        $this->tiers = new ArrayCollection();
    }

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

    public function getNumeroInitial(): ?int
    {
        return $this->numeroInitial;
    }

    public function setNumeroInitial(int $numeroInitial): static
    {
        $this->numeroInitial = $numeroInitial;

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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return Collection<int, Tiers>
     */
    public function getTiers(): Collection
    {
        return $this->tiers;
    }

    public function addTier(Tiers $tier): static
    {
        if (!$this->tiers->contains($tier)) {
            $this->tiers->add($tier);
            $tier->setTypeTiers($this);
        }

        return $this;
    }

    public function removeTier(Tiers $tier): static
    {
        if ($this->tiers->removeElement($tier)) {
            // set the owning side to null (unless already changed)
            if ($tier->getTypeTiers() === $this) {
                $tier->setTypeTiers(null);
            }
        }

        return $this;
    }
}
