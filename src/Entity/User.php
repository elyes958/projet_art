<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(
 *     fields={"email"},
 *     message="Un compte existe déjà avec cette adresse mail"
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Votre pseudo est obligatoire")
     *
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles=["ROLE_USER"];


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Veuillez entrer une adresse mail valide")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\EqualTo(propertyPath="confirm_password",
     *     message="les mots de passe ne correspondent pas")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Commande::class, mappedBy="user")
     */
    private $commandes;


    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $GoogleId;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    // renvoi la chaine de caractère saisie par l'utilisateur et non encodé qu'il renvoi à l'encoder pour qu'il procède au hachage du mot de passe
    public function getSalt()
    {

    }

    // cette méthode vise à nettoyer les mots de passe en texte brut (non hashé) eventuellement stockés
    public function eraseCredentials()
    {

    }

    // cette fonction revoie un tableau de chaine de caractères
    // renvoie les roles accordés à l'utilisateur
    public function getRoles()
    {
        $roles= $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles=$roles;
        return $this;
    }

    /**
     * @return Collection|Commande[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setUser($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->contains($commande)) {
            $this->commandes->removeElement($commande);
            // set the owning side to null (unless already changed)
            if ($commande->getUser() === $this) {
                $commande->setUser(null);
            }
        }

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->GoogleId;
    }

    public function setGoogleId(?string $GoogleId): self
    {
        $this->GoogleId = $GoogleId;

        return $this;
    }

}
