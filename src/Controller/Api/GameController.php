<?php

namespace App\Controller\Api;

use App\Entity\Game;
use App\Repository\GameRepository;
use DateTimeImmutable;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
#[OA\Response(
    response: 200,
    description: 'Retourne toutes les Parties',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Game::class, groups: ['game:read']))
    )
)]
#[OA\Tag(name: 'Parties')]
#[Security(name: 'Bearer')]

class GameController extends AbstractController
{
    #[Route('/games', name: 'app_games', methods: ['GET'])]
    public function index(GameRepository $gameRepository): JsonResponse
    {
        $games = $gameRepository->findAll();

        return $this->json([
            'games' => $games,
        ], context: [
            'groups' => ['game:read']
        ]);
    }

    #[Route('/game/{quizId}/{teamId}/{playerId}/{gameDate}', name: 'app_game_get', methods: ['GET'])]
    public function get(
        int $quizId,
        int $teamId,
        int $playerId,
        string $gameDate,
        GameRepository $gameRepository
    ): JsonResponse {
        try {
            // Convertir la chaîne de la date en objet DateTimeImmutable
            $gameDateObj = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $gameDate);

            if (!$gameDateObj) {
                throw new \Exception('Invalid game date format');
            }

            // Rechercher le jeu par clé primaire composite
            $game = $gameRepository->findOneBy([
                'quizId' => $quizId,
                'teamId' => $teamId,
                'playerId' => $playerId,
                'gameDate' => $gameDateObj,
            ]);

            // Vérifier si le jeu a été trouvé
            if (!$game) {
                return $this->json(['message' => 'Jeu non trouvé'], 404);
            }

            // Retourner le jeu trouvé
            return $this->json($game, 200, [], ['groups' => 'game:read']);
        } catch (\Exception $e) {
            return $this->json(['code' => $e->getCode(), 'message' => $e->getMessage()], 500);
        }
    }

    #[Route('/games', name: 'app_game_add', methods: ['POST'])]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);


            // On traite les données pour créer une nouvelle Partie
            $game = new Game();
            $game->setGameDate(new DateTimeImmutable());
            $game->setPlayerId($data['playerId']);
            $game->setTeamId($data['teamId']);
            $game->setQuizId($data['quizId']);
            $game->setTeamScore($data['teamScore']);
            $game->setPlayerScore($data['playerScore']);
            $game->setSecretCode($data['secretCode']);

            $em->persist($game);
            $em->flush();

            return $this->json($game, context: [
                'groups' => ['game:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/game', name: 'app_game_update', methods: ['PUT', 'PATCH'])]
    public function update(
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse {
        try {
            // Récupérer les paramètres de requête
            $quizId = $request->query->get('quizId');
            $teamId = $request->query->get('teamId');
            $playerId = $request->query->get('playerId');
            $gameDate = $request->query->get('gameDate');

            // Convertir la chaîne de la date en objet DateTimeImmutable
            $gameDateObj = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $gameDate);

            // Récupérer l'objet Game en fonction des paramètres de requête
            $game = $em->getRepository(Game::class)->findOneBy([
                'quizId' => $quizId,
                'teamId' => $teamId,
                'playerId' => $playerId,
                'gameDate' => $gameDateObj,
            ]);

            if (!$game) {
                throw new \Exception('Game not found');
            }

            // Récupérer les données du corps de la requête
            $data = json_decode($request->getContent(), true);

            // Mettre à jour les attributs de la partie
            // (vous pouvez ajouter des vérifications si nécessaire)

            // Mettre à jour la date de la partie si vous voulez la modifier
            if (isset($data['gameDate'])) {
                $gameDateObj = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['gameDate']);
                $game->setGameDate($gameDateObj);
            }

            // Mettre à jour les autres attributs de la partie
            $game->setPlayerId($data['playerId']);
            $game->setTeamId($data['teamId']);
            $game->setQuizId($data['quizId']);
            $game->setTeamScore($data['teamScore']);
            $game->setPlayerScore($data['playerScore']);
            $game->setSecretCode($data['secretCode']);

            // Persister les modifications et flusher l'entité
            $em->flush();

            // Retourner la partie mise à jour en JSON
            return $this->json($game, context: [
                'groups' => ['game:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    #[Route('/game', name: 'app_game_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em): JsonResponse
    {
        try {

            if (isset($data['gameDate'])) {
                $gameDateObj = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['gameDate']);
            }
            // Récupérer les paramètres de requête
            $quizId = $request->query->get('quizId');
            $teamId = $request->query->get('teamId');
            $playerId = $request->query->get('playerId');
            $gameDate = $request->query->get('gameDate');

            // Convertir la chaîne de la date en objet DateTimeImmutable
            $gameDateObj = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $gameDate);

            // Récupérer l'objet Game en fonction des paramètres de requête
            $game = $em->getRepository(Game::class)->findOneBy([
                'quizId' => $quizId,
                'teamId' => $teamId,
                'playerId' => $playerId,
                'gameDate' => $gameDateObj,
            ]);

            if (!$game) {
                throw new \Exception('Game not found');
            }

            // Supprimer l'objet Game
            $em->remove($game);
            $em->flush();

            // Retourner la réponse JSON indiquant que la partie a été supprimée
            return $this->json([
                'code' => 200,
                'message' => 'Partie supprimée'
            ]);
        } catch (\Exception $e) {
            // En cas d'erreur, retourner une réponse JSON avec le code d'erreur et le message d'erreur
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
