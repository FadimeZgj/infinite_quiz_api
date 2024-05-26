<?php

namespace App\Tests\Unit;

use App\Entity\Role;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    private Role $role;

    protected function setUp(): void
    {
        $this->role = new Role();
    }

    public function testGetAndSetName(): void
    {
        $value = 'Admin';
        $this->role->setName($value);
        self::assertEquals($value, $this->role->getName());
    }

    public function testAddAndRemoveUser(): void
    {
        $user = new User();
        $this->role->addUser($user);
        self::assertTrue($this->role->getUsers()->contains($user));

        $this->role->removeUser($user);
        self::assertFalse($this->role->getUsers()->contains($user));
    }
}
