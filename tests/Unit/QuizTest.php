<?php

namespace App\Tests\Unit;

use App\Entity\Player;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Team;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class QuizTest extends TestCase
{
    private Quiz $quiz;

    protected function setUp(): void
    {
        $this->quiz = new Quiz();
    }

    public function testGetAndSetTitle(): void
    {
        $title = 'Test Quiz';
        $this->quiz->setTitle($title);

        self::assertEquals($title, $this->quiz->getTitle());
    }

    public function testIsGroup(): void
    {
        $isGroup = true;
        $this->quiz->setGroup($isGroup);

        self::assertEquals($isGroup, $this->quiz->isGroup());
    }

    public function testGetAndSetUser(): void
    {
        $user = new User();
        $this->quiz->setUser($user);

        self::assertEquals($user, $this->quiz->getUser());
    }

    public function testGetAndAddQuestion(): void
    {
        $question = new Question();
        $this->quiz->addQuestion($question);

        self::assertTrue($this->quiz->getQuestion()->contains($question));
        self::assertEquals($this->quiz, $question->getQuiz());
    }

    public function testRemoveQuestion(): void
    {
        $question = new Question();
        $this->quiz->addQuestion($question);
        $this->quiz->removeQuestion($question);

        self::assertFalse($this->quiz->getQuestion()->contains($question));
        self::assertNull($question->getQuiz());
    }

    public function testGetAndAddPlayer(): void
    {
        $player = $this->createMock(Player::class);
        $this->quiz->addPlayer($player);

        self::assertTrue($this->quiz->getPlayer()->contains($player));
    }

    public function testRemovePlayer(): void
    {
        $player = $this->createMock(Player::class);
        $this->quiz->addPlayer($player);
        $this->quiz->removePlayer($player);

        self::assertFalse($this->quiz->getPlayer()->contains($player));
    }

    public function testGetAndAddTeam(): void
    {
        $team = $this->createMock(Team::class);
        $this->quiz->addTeam($team);

        self::assertTrue($this->quiz->getTeam()->contains($team));
    }

    public function testRemoveTeam(): void
    {
        $team = $this->createMock(Team::class);
        $this->quiz->addTeam($team);
        $this->quiz->removeTeam($team);

        self::assertFalse($this->quiz->getTeam()->contains($team));
    }
}
