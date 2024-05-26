<?php

namespace App\Tests\Unit;

use App\Entity\Organization;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;
use PHPUnit\Framework\TestCase;

class OrganizationTest extends TestCase
{
    private Organization $organization;

    protected function setUp(): void
    {
        $this->organization = new Organization();
    }

    public function testGetAndSetName(): void
    {
        $name = 'Test Organization';
        $this->organization->setName($name);

        self::assertEquals($name, $this->organization->getName());
    }

    public function testGetAndSetCountry(): void
    {
        $country = 'France';
        $this->organization->setCountry($country);

        self::assertEquals($country, $this->organization->getCountry());
    }

    public function testAddAndRemoveUser(): void
    {
        $user = $this->createMock(User::class);
        $user->expects($this->once())
            ->method('setOrganization')
            ->with($this->organization);

        $this->organization->addUser($user);
        self::assertTrue($this->organization->getUser()->contains($user));

        $this->organization->removeUser($user);
        self::assertFalse($this->organization->getUser()->contains($user));
    }

    public function testGetUser(): void
    {
        self::assertInstanceOf(Collection::class, $this->organization->getUser());
    }
}
