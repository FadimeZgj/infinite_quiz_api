<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvatarRepository::class)]
class Avatar
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Gamer>
     */
    #[ORM\ManyToMany(targetEntity: Gamer::class, mappedBy: 'avatars')]
    private Collection $gamers;

    public function __construct()
    {
        $this->gamers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, Gamer>
     */
    public function getGamers(): Collection
    {
        return $this->gamers;
    }

    public function addGamer(Gamer $gamer): static
    {
        if (!$this->gamers->contains($gamer)) {
            $this->gamers->add($gamer);
            $gamer->addAvatar($this);
        }

        return $this;
    }

    public function removeGamer(Gamer $gamer): static
    {
        if ($this->gamers->removeElement($gamer)) {
            $gamer->removeAvatar($this);
        }

        return $this;
    }
}
