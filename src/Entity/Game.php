<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\GameController;
use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GameRepository::class)]
#[ApiResource(operations: [
    new Get(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{uuid}',
        //requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'uuid' => '[a-zA-Z0-9]+']
    ),
    new Put(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{uuid}',
        controller: GameController::class . '::updateGame'
        //requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'uuid' => '[a-zA-Z0-9]+']
    ),
    new Patch(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{uuid}',
        //requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'uuid' => '[a-zA-Z0-9]+']
    ),
    new Delete(
        uriTemplate: '/games/{quizId}/{teamId}/{playerId}/{uuid}',
        //requirements: ['quizId' => '\d+', 'teamId' => '\d+', 'playerId' => '\d+', 'uuid' => '[a-zA-Z0-9]+']
    ),
    new GetCollection(),
    new Post(),

])]
//#[ApiResource]

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

    #[ORM\Id]
    #[ORM\Column(type: 'string')]
    #[ApiProperty(identifier: true)]
    private ?string $uuid = null;

    #[ORM\Column(type: "datetime_immutable")]
    #[Assert\NotBlank]
    private ?\DateTimeImmutable $gameDate = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    private ?int $secretCode = null;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $teamScore = null;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank]
    private ?int $playerScore = null;

    public function __construct()
    {
        $this->uuid = Uuid::v4()->__toString();
        $this->gameDate = new DateTimeImmutable();
    }

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
    public function getSecretCode(): ?int
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
    public function getTeamScore(): ?int
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
    public function getPlayerScore(): ?int
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
    public function getQuizId(): ?int
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
    public function getTeamId(): ?int
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
    public function getPlayerId(): ?int
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

    /**
     * Get the value of uuid
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * Set the value of uuid
     *
     * @return  self
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }
}
