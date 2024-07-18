<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(processor: UserPasswordHasher::class, validationContext: ['groups' => ['Default', 'user:create']]),
        new Get(validationContext: ['groups' => ['Default', 'user:read']]),
        new Put(processor: UserPasswordHasher::class),
        new Patch(processor: UserPasswordHasher::class),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:create', 'user:update']],
)]

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Groups(['user:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    // #[Assert\NotBlank]
    private array $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Assert\NotBlank(groups: ['user:create'])]
    #[Groups(['user:create', 'user:update'])]
    private ?string $plainPassword = null;

    #[Assert\Blank]
    // #[Assert\IsNull]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?string $honneypot = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?Organization $organization = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?Role $role = null;

    /**
     * @var Collection<int, Badge>
     */
    #[ORM\ManyToMany(targetEntity: Badge::class, inversedBy: 'users')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private Collection $badge;

    /**
     * @var Collection<int, Quiz>
     */
    #[ORM\OneToMany(targetEntity: Quiz::class, mappedBy: 'user')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private Collection $quiz;

    /**
     * @var Collection<int, Player>
     */
    #[ORM\OneToMany(targetEntity: Player::class, mappedBy: 'user')]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private Collection $player;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    #[Assert\NotBlank]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?string $service = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['user:read', 'user:create', 'user:update'])]
    private ?string $job = null;

    public function __construct()
    {
        $this->badge = new ArrayCollection();
        $this->quiz = new ArrayCollection();
        $this->player = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        // $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getHonneypot(): ?string
    {
        return $this->honneypot;
    }

    public function setHonneypot(?string $honneypot): self
    {
        $this->honneypot = $honneypot;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function getOrganization(): ?Organization
    {
        return $this->organization;
    }

    public function setOrganization(?Organization $organization): static
    {
        $this->organization = $organization;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection<int, Badge>
     */
    public function getBadge(): Collection
    {
        return $this->badge;
    }

    public function addBadge(Badge $badge): static
    {
        if (!$this->badge->contains($badge)) {
            $this->badge->add($badge);
        }

        return $this;
    }

    public function removeBadge(Badge $badge): static
    {
        $this->badge->removeElement($badge);

        return $this;
    }

    /**
     * @return Collection<int, Quiz>
     */
    public function getQuiz(): Collection
    {
        return $this->quiz;
    }

    public function addQuiz(Quiz $quiz): static
    {
        if (!$this->quiz->contains($quiz)) {
            $this->quiz->add($quiz);
            $quiz->setUser($this);
        }

        return $this;
    }

    public function removeQuiz(Quiz $quiz): static
    {
        if ($this->quiz->removeElement($quiz)) {
            // set the owning side to null (unless already changed)
            if ($quiz->getUser() === $this) {
                $quiz->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayer(): Collection
    {
        return $this->player;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->player->contains($player)) {
            $this->player->add($player);
            $player->setUser($this);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        if ($this->player->removeElement($player)) {
            // set the owning side to null (unless already changed)
            if ($player->getUser() === $this) {
                $player->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getJob(): ?string
    {
        return $this->job;
    }

    public function setJob(?string $job): static
    {
        $this->job = $job;

        return $this;
    }
}
