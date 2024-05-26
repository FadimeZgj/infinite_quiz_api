<?php

namespace App\Tests\Unit;

use App\Entity\Avatar;
use App\Entity\Player;
use App\Entity\Quiz;
use App\Entity\Team;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class PlayerTest extends TestCase
{
    private Player $player;

    protected function setUp(): void
    {
        $this->player = new Player();
    }

    public function testGetAndSetPseudo(): void
    {
        $pseudo = 'test_pseudo';
        $this->player->setPseudo($pseudo);

        self::assertEquals($pseudo, $this->player->getPseudo());
    }

    public function testGetAndSetUser(): void
    {
        $user = new User();
        $this->player->setUser($user);

        self::assertEquals($user, $this->player->getUser());
    }

    public function testGetAndSetAvatar(): void
    {
        $avatar = new Avatar();
        $this->player->setAvatar($avatar);

        self::assertEquals($avatar, $this->player->getAvatar());
    }

    public function testGetAndAddTeam(): void
    {
        $team = new Team();
        $this->player->addTeam($team);

        self::assertTrue($this->player->getTeam()->contains($team));
    }

    public function testRemoveTeam(): void
    {
        $team = new Team();
        $this->player->addTeam($team);
        $this->player->removeTeam($team);

        self::assertFalse($this->player->getTeam()->contains($team));
    }

    public function testGetAndAddQuiz(): void
    {
        $quiz = new Quiz();
        $this->player->addQuiz($quiz);

        self::assertTrue($this->player->getQuizzes()->contains($quiz));
    }

    public function testRemoveQuiz(): void
    {
        $quiz = new Quiz();
        $this->player->addQuiz($quiz);
        $this->player->removeQuiz($quiz);

        self::assertFalse($this->player->getQuizzes()->contains($quiz));
    }
}
