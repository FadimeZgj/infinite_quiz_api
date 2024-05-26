<?php

namespace App\Tests\Unit;

use App\Entity\Game;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private Game $game;

    protected function setUp(): void
    {
        $this->game = new Game();
    }

    public function testGetAndSetGameDate(): void
    {
        $gameDate = new DateTimeImmutable('2024-05-26');
        $this->game->setGameDate($gameDate);

        self::assertEquals($gameDate, $this->game->getGameDate());
    }

    public function testGetAndSetSecretCode(): void
    {
        $secretCode = 1234;
        $this->game->setSecretCode($secretCode);

        self::assertEquals($secretCode, $this->game->getSecretCode());
    }

    public function testGetAndSetTeamScore(): void
    {
        $teamScore = 56;
        $this->game->setTeamScore($teamScore);

        self::assertEquals($teamScore, $this->game->getTeamScore());
    }

    public function testGetAndSetPlayerScore(): void
    {
        $playerScore = 78;
        $this->game->setPlayerScore($playerScore);

        self::assertEquals($playerScore, $this->game->getPlayerScore());
    }

    public function testGetAndSetQuizId(): void
    {
        $quizId = 1;
        $this->game->setQuizId($quizId);

        self::assertEquals($quizId, $this->game->getQuizId());
    }

    public function testGetAndSetTeamId(): void
    {
        $teamId = 2;
        $this->game->setTeamId($teamId);

        self::assertEquals($teamId, $this->game->getTeamId());
    }

    public function testGetAndSetPlayerId(): void
    {
        $playerId = 3;
        $this->game->setPlayerId($playerId);

        self::assertEquals($playerId, $this->game->getPlayerId());
    }

    public function testGetAndSetUuid(): void
    {
        $uuid = '550e8400-e29b-41d4-a716-446655440000';
        $this->game->setUuid($uuid);

        self::assertEquals($uuid, $this->game->getUuid());
    }
}
