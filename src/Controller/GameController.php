<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class GameController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/api/games/{quizId}/{teamId}/{playerId}/{uuid}", methods={"PUT"})
     */
    public function updateGame(int $quizId, int $teamId, int $playerId, string $uuid, Request $request): JsonResponse
    {
        // Clear the EntityManager to avoid stale data issues
        $this->entityManager->clear();

        // Fetch the entity from the database
        $game = $this->entityManager->getRepository(Game::class)->findOneBy([
            'quizId' => $quizId,
            'teamId' => $teamId,
            'playerId' => $playerId,
            'uuid' => $uuid,
        ]);

        if (!$game) {
            return $this->json(['error' => 'Game not found'], JsonResponse::HTTP_NOT_FOUND);
        }

        // Update the entity with new data
        $data = json_decode($request->getContent(), true);

        if (isset($data['secretCode'])) {
            $game->setSecretCode($data['secretCode']);
        }

        if (isset($data['teamScore'])) {
            $game->setTeamScore($data['teamScore']);
        }

        if (isset($data['playerScore'])) {
            $game->setPlayerScore($data['playerScore']);
        }

        // Persist and flush changes to the database
        $this->entityManager->flush();

        return $this->json($game);
    }
}
