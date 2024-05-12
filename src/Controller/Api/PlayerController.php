<?php

namespace App\Controller\Api;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use OpenApi\Attributes as OA;
use Doctrine\ORM\EntityManagerInterface;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/api')]
#[OA\Response(
    response: 200,
    description: 'Retourne tous les joueurs',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Player::class, groups: ['player:read']))
    )
)]
#[OA\Tag(name: 'Joueurs')]
#[Security(name: 'Bearer')]
class PlayerController extends AbstractController
{
    #[Route('/players', name: 'app_players', methods: ['GET'])]

    public function index(PlayerRepository $playerRepository): JsonResponse
    {
        $players = $playerRepository->findAll();

        return $this->json([
            'players' => $players,
        ], context: [
            'groups' => ['player:read']
        ]);
    }

    #[Route('/player/{id}', name: 'app_player_get', methods: ['GET'])]
    #[OA\Tag(name: 'Joueurs')]
    public function get(Player $player): JsonResponse
    {
        return $this->json($player, context: [
            'groups' => ['player:read'],
        ]);
    }

    #[Route('/players', name: 'app_player_add', methods: ['POST'])]
    #[OA\Tag(name: 'Joueurs')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ): JsonResponse {
        try {

            // Récupérez l'utilisateur actuel
            $user = $tokenStorage->getToken()->getUser();

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle structure
            $player = new Player();
            $player->setPseudo($data['pseudo']);
            $player->setUser($user);

            $em->persist($player);
            $em->flush();

            return $this->json($player, context: [
                'groups' => ['player:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/player/{id}', name: 'app_player_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Joueurs')]
    public function update(
        Player $player,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Structure
            $player->setPseudo($data['pseudo']);

            $em->persist($player);
            $em->flush();

            return $this->json($player, context: [
                'groups' => ['player:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/player/{id}', name: 'app_Player_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Joueurs')]
    public function delete(Player $player, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($player);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Joueur supprimé'
        ]);
    }
}
