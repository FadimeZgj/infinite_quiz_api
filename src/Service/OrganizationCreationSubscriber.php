<?php

// src/EventSubscriber/OrganizationCreationSubscriber.php

namespace App\EventSubscriber;

use ApiPlatform\Symfony\EventListener\EventPriorities;
use App\Entity\Organization;
use App\Entity\Role;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class OrganizationCreationSubscriber implements EventSubscriberInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addRoleToOwner', EventPriorities::POST_WRITE],
        ];
    }

    public function addRoleToOwner(ViewEvent $event): void
    {
        $organization = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$organization instanceof Organization || Request::METHOD_POST !== $method) {
            return;
        }

        $owner = $organization->getOwner();
        if ($owner) {
            $role = $this->entityManager->getRepository(Role::class)->findOneBy(['name' => 'ADMIN_ORGANIZATION']);
            if ($role) {
                $owner->setRole($role);
                $this->entityManager->persist($owner);
                $this->entityManager->flush();
            }
        }
    }
}
