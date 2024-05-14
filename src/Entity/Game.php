<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(operations: [
    new Get(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{gameDate}',
        requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'gameDate' => '\d+'],
        options: ['my_option' => 'my_option_value'],
        schemes: ['https'],
        host: '{subdomain}.api-platform.com'
    ),
    new Delete(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{gameDate}',
        requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'gameDate' => '\d+'],
    ),
    new GetCollection(),
    new Post(),
    new Put(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{gameDate}',
        requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'gameDate' => '\d+'],
    ),
    new Patch(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{gameDate}',
        requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'gameDate' => '\d+'],
    )
])]


class Game
{

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ApiProperty(identifier: true)]
    private ?int $quizId = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ApiProperty(identifier: true)]
    private ?int $teamId = null;

    #[ORM\Id]
    #[ORM\Column(type: "integer")]
    #[ApiProperty(identifier: true)]
    private ?int $playerId = null;

    #[ORM\Column(type: "datetime_immutable")]
    #[ApiProperty(identifier: true)]
    private ?\DateTimeImmutable $gameDate = null;

    #[ORM\Column(type: "integer")]
    private ?int $secretCode = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $teamScore = null;

    #[ORM\Column(type: "integer")]
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
