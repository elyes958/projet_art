<?php

namespace App\Entity;

use App\Repository\OeuvreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=OeuvreRepository::class)
 */
class Oeuvre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="oeuvres")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Veuillez saisir un nom d'oeuvre")
     * @Assert\Length(max=30, maxMessage="Le nom de l'oeuvre ne doit pas dépasser 30 caractères")
     */
    private $nom;

    /**
     * @ORM\Column(type="float")
     * @Assert\NotBlank(message="Veuillez saisir un prix pour l'oeuvre")
     * @Assert\Type(type="integer", message="Le prix de l'oeuvre doit être un entier")
     */
    private $prix;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Veuillez saisir une description de l'oeuvre")
     * @Assert\Length(max=300, maxMessage="La description de l'oeuvre ne doit pas dépasser 300 caractères",min=30, minMessage="Veuillez remplir une description d'un minimum de 30 caractères")
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
