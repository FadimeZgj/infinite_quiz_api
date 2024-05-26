<?php

namespace App\Tests\Unit;

use App\Entity\Avatar;
use App\Entity\Player;
use PHPUnit\Framework\TestCase;

class AvatarTest extends TestCase
{
    private Avatar $avatar;

    protected function setUp(): void
    {
        parent::setUp();

        $this->avatar = new Avatar();
    }

    public function testGetName(): void
    {
        $value = 'Avatar 1';

        $response = $this->avatar->setName($value);
        $getName = $this->avatar->getName();

        self::assertInstanceOf(Avatar::class, $response);
        self::assertEquals($value, $getName);
    }

    public function testGetUrl(): void
    {
        $value = 'http://example.com/avatar1.png';

        $response = $this->avatar->setUrl($value);
        $getUrl = $this->avatar->getUrl();

        self::assertInstanceOf(Avatar::class, $response);
        self::assertEquals($value, $getUrl);
    }

    public function testAddPlayer(): void
    {
        $player = $this->createMock(Player::class);
        $player->expects(self::once())->method('setAvatar')->with($this->avatar);

        $response = $this->avatar->addPlayer($player);

        self::assertInstanceOf(Avatar::class, $response);
        self::assertCount(1, $this->avatar->getPlayers());
        self::assertTrue($this->avatar->getPlayers()->contains($player));
    }

    public function testRemovePlayer(): void
    {
        // Création d'un mock de Player
        $player = $this->createMock(Player::class);

        // Simulation de la méthode getAvatar pour retourner cet avatar
        $player->method('getAvatar')->willReturn($this->avatar);

        // Ajout du joueur à l'avatar
        $this->avatar->addPlayer($player);

        // Vérification que setAvatar(null) est appelé lors de la suppression
        $player->expects(self::once())->method('setAvatar')->with(null);

        // Appel de la méthode removePlayer
        $response = $this->avatar->removePlayer($player);

        // Assertions
        self::assertInstanceOf(Avatar::class, $response);
        self::assertCount(0, $this->avatar->getPlayers());
        self::assertFalse($this->avatar->getPlayers()->contains($player));
    }

    public function testGetPlayers(): void
    {
        $player1 = $this->createMock(Player::class);
        $player2 = $this->createMock(Player::class);

        $this->avatar->addPlayer($player1);
        $this->avatar->addPlayer($player2);

        $players = $this->avatar->getPlayers();

        self::assertCount(2, $players);
        self::assertTrue($players->contains($player1));
        self::assertTrue($players->contains($player2));
    }
}
