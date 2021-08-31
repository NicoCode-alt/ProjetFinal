<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity("username")
 * @UniqueEntity("email")
 */
class User implements UserInterface
{

    /**
    * toString
    * @return string
    */
    public function __toString() {

        return $this->username;

    }



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\EqualTo(propertyPath="passwordConfirm", message="Le mot de passe doit être identique")
     * 
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Le mot de passe doit être identique")
     */
    private $passwordConfirm;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="user", orphanRemoval=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity=Critic::class, mappedBy="user", orphanRemoval=true)
     */
    private $critics;

    /**
     * @ORM\OneToMany(targetEntity=Like::class, mappedBy="commentary")
     */
    private $likes_commentary;


    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->critics = new ArrayCollection();
        $this->likes_commentary = new ArrayCollection();
    }

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

    public function getPasswordConfirm(): ?string
    {
        return $this->passwordConfirm;
    }

    public function setPasswordConfirm(string $passwordConfirm): self
    {
        $this->passwordConfirm = $passwordConfirm;

        return $this;
    }

    public function getSalt()
    {
        
    }

    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    public function getUserIdentifier()
    {
        return $this->username;
    }

    public function eraseCredentials()
    {
        
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setUser($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getUser() === $this) {
                $like->setUser(null);
            }
        }

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(\DateTimeInterface $birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|Critic[]
     */
    public function getCritics(): Collection
    {
        return $this->critics;
    }

    public function addCritic(Critic $critic): self
    {
        if (!$this->critics->contains($critic)) {
            $this->critics[] = $critic;
            $critic->setUser($this);
        }

        return $this;
    }

    public function removeCritic(Critic $critic): self
    {
        if ($this->critics->removeElement($critic)) {
            // set the owning side to null (unless already changed)
            if ($critic->getUser() === $this) {
                $critic->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Like[]
     */
    public function getLikesCommentary(): Collection
    {
        return $this->likes_commentary;
    }

    public function addLikesCommentary(Like $likesCommentary): self
    {
        if (!$this->likes_commentary->contains($likesCommentary)) {
            $this->likes_commentary[] = $likesCommentary;
            $likesCommentary->setCommentary($this);
        }

        return $this;
    }

    public function removeLikesCommentary(Like $likesCommentary): self
    {
        if ($this->likes_commentary->removeElement($likesCommentary)) {
            // set the owning side to null (unless already changed)
            if ($likesCommentary->getCommentary() === $this) {
                $likesCommentary->setCommentary(null);
            }
        }

        return $this;
    }

   
}
