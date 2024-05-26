<?php

namespace App\Tests\Unit;

use App\Entity\Badge;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class BadgeTest extends TestCase
{
    private Badge $badge;

    protected function setUp(): void
    {
        $this->badge = new Badge();
    }

    public function testGetName(): void
    {
        $value = 'Badge 1';

        $response = $this->badge->setName($value);
        $getName = $this->badge->getName();

        self::assertInstanceOf(Badge::class, $response);
        self::assertEquals($value, $getName);
    }

    public function testGetUrl(): void
    {
        $value = 'http://example.com/badge.png';

        $response = $this->badge->setUrl($value);
        $getUrl = $this->badge->getUrl();

        self::assertInstanceOf(Badge::class, $response);
        self::assertEquals($value, $getUrl);
    }

    public function testAddUser(): void
    {
        $user = $this->createMock(User::class);

        $user->expects(self::once())
            ->method('addBadge')
            ->with($this->badge);

        $response = $this->badge->addUser($user);

        self::assertInstanceOf(Badge::class, $response);
        self::assertCount(1, $this->badge->getUsers());
        self::assertTrue($this->badge->getUsers()->contains($user));
    }

    public function testRemoveUser(): void
    {
        $user = $this->createMock(User::class);

        $user->method('addBadge')
            ->with($this->badge);

        $this->badge->addUser($user);

        $user->expects(self::once())
            ->method('removeBadge')
            ->with($this->badge);

        $response = $this->badge->removeUser($user);

        self::assertInstanceOf(Badge::class, $response);
        self::assertCount(0, $this->badge->getUsers());
        self::assertFalse($this->badge->getUsers()->contains($user));
    }
}
