<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<User>
 */
final class UserPasswordHasher implements ProcessorInterface
{
    private ProcessorInterface $processor;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        ProcessorInterface $processor,
        UserPasswordHasherInterface $passwordHasher
    ) {
        $this->processor = $processor;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param User $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): User
    {
        // Check if all required fields are set
        if (!$data->getFirstname()) {
            throw new \InvalidArgumentException('Firstname is required.');
        }

        // if (!$data->getLastname()) {
        //     throw new \InvalidArgumentException('Lastname is required.');
        // }

        if ($data->getPlainPassword()) {
            $hashedPassword = $this->passwordHasher->hashPassword(
                $data,
                $data->getPlainPassword()
            );
            $data->setPassword($hashedPassword);
            $data->eraseCredentials();
        }

        return $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
