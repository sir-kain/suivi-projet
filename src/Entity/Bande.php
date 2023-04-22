<?php

namespace App\Entity;

use App\Repository\BandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use IntlDateFormatter;

#[ORM\Entity(repositoryClass: BandeRepository::class)]
class Bande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nb_poussins = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column]
    private ?int $nb_mortalite = null;

    private ?int $nb_jours = null;

    #[ORM\OneToMany(mappedBy: 'bande', targetEntity: Depense::class, orphanRemoval: true)]
    private Collection $depenses;

    #[ORM\OneToMany(mappedBy: 'bande', targetEntity: Vente::class, orphanRemoval: true)]
    private Collection $ventes;

    public function __construct()
    {
        $this->depenses = new ArrayCollection();
        $this->ventes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbPoussins(): ?int
    {
        return $this->nb_poussins;
    }

    public function setNbPoussins(int $nb_poussins): self
    {
        $this->nb_poussins = $nb_poussins;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getNbMortalite(): ?int
    {
        return $this->nb_mortalite;
    }

    public function setNbMortalite(int $nb_mortalite): self
    {
        $this->nb_mortalite = $nb_mortalite;

        return $this;
    }

    public function getNbJours(): ?int
    {
        return 10;
    }

    /**
     * @return Collection<int, Depense>
     */
    public function getDepenses(): Collection
    {
        return $this->depenses;
    }

    public function addDepense(Depense $depense): self
    {
        if (!$this->depenses->contains($depense)) {
            $this->depenses->add($depense);
            $depense->setBande($this);
        }

        return $this;
    }

    public function removeDepense(Depense $depense): self
    {
        if ($this->depenses->removeElement($depense)) {
            // set the owning side to null (unless already changed)
            if ($depense->getBande() === $this) {
                $depense->setBande(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vente>
     */
    public function getVentes(): Collection
    {
        return $this->ventes;
    }

    public function addVente(Vente $vente): self
    {
        if (!$this->ventes->contains($vente)) {
            $this->ventes->add($vente);
            $vente->setBande($this);
        }

        return $this;
    }

    public function removeVente(Vente $vente): self
    {
        if ($this->ventes->removeElement($vente)) {
            // set the owning side to null (unless already changed)
            if ($vente->getBande() === $this) {
                $vente->setBande(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        $formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::MEDIUM, IntlDateFormatter::NONE);
        $date = $formatter->format($this->getDateDebut());
        return  "Bande {$this->nb_poussins} du $date";
    }
}
