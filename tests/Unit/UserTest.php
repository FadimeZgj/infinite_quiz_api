<?php

namespace App\Tests\Unit;

use App\Entity\Badge;
use App\Entity\Organization;
use App\Entity\Player;
use App\Entity\Quiz;
use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        $this->user = new User();
    }

    public function testGetAndSetEmail(): void
    {
        $value = 'test@example.com';
        $this->user->setEmail($value);
        self::assertEquals($value, $this->user->getEmail());
    }

    public function testGetAndSetRoles(): void
    {
        $roles = ['ROLE_ADMIN', 'ROLE_USER'];
        $this->user->setRoles($roles);
        self::assertEquals($roles, $this->user->getRoles());
    }

    public function testGetAndSetPassword(): void
    {
        $password = 'test_password';
        $this->user->setPassword($password);
        self::assertEquals($password, $this->user->getPassword());
    }

    public function testGetAndSetPlainPassword(): void
    {
        $plainPassword = 'test_plain_password';
        $this->user->setPlainPassword($plainPassword);
        self::assertEquals($plainPassword, $this->user->getPlainPassword());
    }

    public function testGetAndSetOrganization(): void
    {
        $organization = new Organization();
        $this->user->setOrganization($organization);
        self::assertEquals($organization, $this->user->getOrganization());
    }

    public function testGetAndSetRole(): void
    {
        $role = new Role();
        $this->user->setRole($role);
        self::assertEquals($role, $this->user->getRole());
    }

    public function testAddAndRemoveBadge(): void
    {
        $badge = new Badge();
        $this->user->addBadge($badge);
        self::assertTrue($this->user->getBadge()->contains($badge));

        $this->user->removeBadge($badge);
        self::assertFalse($this->user->getBadge()->contains($badge));
    }

    public function testAddAndRemoveQuiz(): void
    {
        $quiz = new Quiz();
        $this->user->addQuiz($quiz);
        self::assertTrue($this->user->getQuiz()->contains($quiz));

        $this->user->removeQuiz($quiz);
        self::assertFalse($this->user->getQuiz()->contains($quiz));
    }

    public function testAddAndRemovePlayer(): void
    {
        $player = new Player();
        $this->user->addPlayer($player);
        self::assertTrue($this->user->getPlayer()->contains($player));

        $this->user->removePlayer($player);
        self::assertFalse($this->user->getPlayer()->contains($player));
    }

    public function testGetAndSetFirstname(): void
    {
        $value = 'John';
        $this->user->setFirstname($value);
        self::assertEquals($value, $this->user->getFirstname());
    }

    public function testGetAndSetLastname(): void
    {
        $value = 'Doe';
        $this->user->setLastname($value);
        self::assertEquals($value, $this->user->getLastname());
    }

    public function testGetAndSetService(): void
    {
        $value = 'Some Service';
        $this->user->setService($value);
        self::assertEquals($value, $this->user->getService());
    }

    public function testGetAndSetJob(): void
    {
        $value = 'Some Job';
        $this->user->setJob($value);
        self::assertEquals($value, $this->user->getJob());
    }
}
