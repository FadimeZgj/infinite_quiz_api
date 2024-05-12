<?php

namespace App\Entity;

use App\Repository\GameRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[Groups('game:read')]
    private ?int $quizId = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[Groups('game:read')]
    private ?int $teamId = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[Groups('game:read')]
    private ?int $playerId = null;

    #[ORM\Column(type: "datetime_immutable")]
    #[Groups('game:read')]
    private ?\DateTimeImmutable $gameDate = null;

    #[ORM\Column(type: "integer")]
    #[Groups('game:read')]
    private ?int $secretCode = null;

    #[ORM\Column(type: "integer", nullable: true)]
    #[Groups('game:read')]
    private ?int $teamScore = null;

    #[ORM\Column(type: "integer")]
    #[Groups('game:read')]
    private ?int $playerScore = null;

    /**
     * Get the value of gameDate
     */
    public function getGameDate(): ?\DateTimeImmutable
    {
        return $this->gameDate;
    }

    /**
     * Set the value of gameDate
     *
     * @return  self
     */
    public function setGameDate($gameDate)
    {
        $this->gameDate = $gameDate;

        return $this;
    }

    /**
     * Get the value of secretCode
     */
    public function getSecretCode()
    {
        return $this->secretCode;
    }

    /**
     * Set the value of secretCode
     *
     * @return  self
     */
    public function setSecretCode($secretCode)
    {
        $this->secretCode = $secretCode;

        return $this;
    }

    /**
     * Get the value of teamScore
     */
    public function getTeamScore()
    {
        return $this->teamScore;
    }

    /**
     * Set the value of teamScore
     *
     * @return  self
     */
    public function setTeamScore($teamScore)
    {
        $this->teamScore = $teamScore;

        return $this;
    }

    /**
     * Get the value of playerScore
     */
    public function getPlayerScore()
    {
        return $this->playerScore;
    }

    /**
     * Set the value of playerScore
     *
     * @return  self
     */
    public function setPlayerScore($playerScore)
    {
        $this->playerScore = $playerScore;

        return $this;
    }

    /**
     * Get the value of quizId
     */
    public function getQuizId()
    {
        return $this->quizId;
    }

    /**
     * Set the value of quizId
     *
     * @return  self
     */
    public function setQuizId($quizId)
    {
        $this->quizId = $quizId;

        return $this;
    }

    /**
     * Get the value of teamId
     */
    public function getTeamId()
    {
        return $this->teamId;
    }

    /**
     * Set the value of teamId
     *
     * @return  self
     */
    public function setTeamId($teamId)
    {
        $this->teamId = $teamId;

        return $this;
    }

    /**
     * Get the value of playerId
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * Set the value of playerId
     *
     * @return  self
     */
    public function setPlayerId($playerId)
    {
        $this->playerId = $playerId;

        return $this;
    }
}
