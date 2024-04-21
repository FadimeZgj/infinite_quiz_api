<?php

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $Title = null;

    #[ORM\Column]
    private ?bool $isGroup = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'quiz')]
    private Collection $questions;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    private ?User $users = null;

    /**
     * @var Collection<int, Team>
     */
    #[ORM\ManyToMany(targetEntity: Team::class, inversedBy: 'quizzes')]
    private Collection $teams;

    /**
     * @var Collection<int, Player>
     */
    #[ORM\ManyToMany(targetEntity: Player::class, inversedBy: 'quizzes')]
    private Collection $players;

    #[ORM\Column(type: "datetime_immutable")]
    private ?\DateTimeImmutable $gameDate = null;

    #[ORM\Column(type: "integer")]
    private ?int $secretCode = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $teamScore = null;

    #[ORM\Column(type: "integer")]
    private ?int $playerScore = null;


    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->teams = new ArrayCollection();
        $this->players = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->Title;
    }

    public function setTitle(string $Title): static
    {
        $this->Title = $Title;

        return $this;
    }

    public function isGroup(): ?bool
    {
        return $this->isGroup;
    }

    public function setGroup(bool $isGroup): static
    {
        $this->isGroup = $isGroup;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, Team>
     */
    public function getTeams(): Collection
    {
        return $this->teams;
    }

    public function addTeam(Team $team): static
    {
        if (!$this->teams->contains($team)) {
            $this->teams->add($team);
        }

        return $this;
    }

    public function removeTeam(Team $team): static
    {
        $this->teams->removeElement($team);

        return $this;
    }

    /**
     * @return Collection<int, Player>
     */
    public function getPlayers(): Collection
    {
        return $this->players;
    }

    public function addPlayer(Player $player): static
    {
        if (!$this->players->contains($player)) {
            $this->players->add($player);
        }

        return $this;
    }

    public function removePlayer(Player $player): static
    {
        $this->players->removeElement($player);

        return $this;
    }

    // Getters et setters pour gameDate
    public function getGameDate(): ?\DateTimeImmutable
    {
        return $this->gameDate;
    }

    public function setGameDate(?\DateTimeImmutable $gameDate): self
    {
        $this->gameDate = $gameDate;

        return $this;
    }

    // Getters et setters pour secretCode
    public function getSecretCode(): ?int
    {
        return $this->secretCode;
    }

    public function setSecretCode(?int $secretCode): self
    {
        $this->secretCode = $secretCode;

        return $this;
    }

    // Getters et setters pour teamScore
    public function getTeamScore(): ?int
    {
        return $this->teamScore;
    }

    public function setTeamScore(?int $teamScore): self
    {
        $this->teamScore = $teamScore;

        return $this;
    }

    // Getters et setters pour playerScore
    public function getPlayerScore(): ?int
    {
        return $this->playerScore;
    }

    public function setPlayerScore(?int $playerScore): self
    {
        $this->playerScore = $playerScore;

        return $this;
    }
}
