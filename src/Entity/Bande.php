<?php

namespace App\Entity;

use App\Repository\BandeRepository;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use IntlDateFormatter;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BandeRepository::class)]
class Bande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column]
    private ?int $nb_poussins = null;

    #[Assert\NotNull]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $date_debut = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column]
    private int $nb_mortalite = 0;

    #[ORM\OneToMany(mappedBy: 'bande', targetEntity: Depense::class, orphanRemoval: true)]
    #[ORM\OrderBy(['created_at' => 'DESC'])]
    private Collection $depenses;

    #[ORM\OneToMany(mappedBy: 'bande', targetEntity: Vente::class, orphanRemoval: true)]
    #[ORM\OrderBy(['created_at' => 'DESC'])]
    private Collection $ventes;

    #[Assert\GreaterThan(
        propertyPath: 'date_debut',
        message: "La date de cloture doit être supérieure à la date de début."
    )]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $date_cloture = null;

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

    public function getNbMortalite(): int
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
        if ($this->isClotured()) {
            return date_diff($this->getDateCloture(), $this->getDateDebut())->days;
        }
        return date_diff(new DateTime(), $this->getDateDebut())->days;
    }

    public function isClotured(): bool
    {
        return $this->date_cloture !== null;
    }

    public function getDateCloture(): ?DateTimeInterface
    {
        return $this->date_cloture;
    }

    public function setDateCloture(?DateTimeInterface $date_cloture): self
    {
        $this->date_cloture = $date_cloture;

        return $this;
    }

    public function getDateDebut(): ?DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
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
        return "Bande {$this->nb_poussins} du $date";
    }
}
