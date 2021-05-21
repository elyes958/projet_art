<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity=Oeuvre::class, mappedBy="categorie", orphanRemoval=true)
     */
    private $oeuvres;

    public function __construct()
    {
        $this->oeuvres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection|Oeuvre[]
     */
    public function getOeuvres(): Collection
    {
        return $this->oeuvres;
    }

    public function addOeuvre(Oeuvre $oeuvre): self
    {
        if (!$this->oeuvres->contains($oeuvre)) {
            $this->oeuvres[] = $oeuvre;
            $oeuvre->setCategorie($this);
        }

        return $this;
    }

    public function removeOeuvre(Oeuvre $oeuvre): self
    {
        if ($this->oeuvres->removeElement($oeuvre)) {
            // set the owning side to null (unless already changed)
            if ($oeuvre->getCategorie() === $this) {
                $oeuvre->setCategorie(null);
            }
        }

        return $this;
    }
}
