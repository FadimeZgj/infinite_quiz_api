<?php

namespace App\Entity;

use App\Repository\GamerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamerRepository::class)]
class Gamer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $pseudo = null;

    /**
     * @var Collection<int, Avatar>
     */
    #[ORM\ManyToMany(targetEntity: Avatar::class, inversedBy: 'gamers')]
    private Collection $avatars;

    #[ORM\ManyToOne(inversedBy: 'gamers')]
    private ?User $user = null;


    public function __construct()
    {
        $this->avatars = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection<int, Avatar>
     */
    public function getAvatars(): Collection
    {
        return $this->avatars;
    }

    public function addAvatar(Avatar $avatar): static
    {
        if (!$this->avatars->contains($avatar)) {
            $this->avatars->add($avatar);
        }

        return $this;
    }

    public function removeAvatar(Avatar $avatar): static
    {
        $this->avatars->removeElement($avatar);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
