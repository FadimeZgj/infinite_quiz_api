<?php

namespace App\Tests\Unit;

use App\Entity\Avatar;
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
}
