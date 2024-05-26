<?php

namespace App\Tests\Unit;

use App\Entity\Player;
use App\Entity\Quiz;
use App\Entity\Team;
use PHPUnit\Framework\TestCase;

class TeamTest extends TestCase
{
    private Team $team;

    protected function setUp(): void
    {
        $this->team = new Team();
    }

    public function testGetAndSetName(): void
    {
        $value = 'Team A';
        $this->team->setName($value);
        self::assertEquals($value, $this->team->getName());
    }

    public function testAddAndRemovePlayer(): void
    {
        $player = new Player();
        $this->team->addPlayer($player);
        self::assertTrue($this->team->getPlayers()->contains($player));

        $this->team->removePlayer($player);
        self::assertFalse($this->team->getPlayers()->contains($player));
    }

    public function testAddAndRemoveQuiz(): void
    {
        $quiz = new Quiz();
        $this->team->addQuiz($quiz);
        self::assertTrue($this->team->getQuizzes()->contains($quiz));

        $this->team->removeQuiz($quiz);
        self::assertFalse($this->team->getQuizzes()->contains($quiz));
    }
}
