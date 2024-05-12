<?php

namespace App\Controller\Api;

use App\Entity\Team;
use App\Repository\TeamRepository;
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
    description: 'Retourne toutes les Equipes',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Team::class, groups: ['team:read']))
    )
)]
#[OA\Tag(name: 'Equipes')]
#[Security(name: 'Bearer')]

class TeamController extends AbstractController
{
    #[Route('/teams', name: 'app_teams', methods: ['GET'])]
    public function index(TeamRepository $teamRepository): JsonResponse
    {
        $teams = $teamRepository->findAll();

        return $this->json([
            'teams' => $teams,
        ], context: [
            'groups' => ['team:read']
        ]);
    }

    #[Route('/team/{id}', name: 'app_team_get', methods: ['GET'])]
    #[OA\Tag(name: 'Equipes')]
    public function get(Team $team): JsonResponse
    {
        return $this->json($team, context: [
            'groups' => ['team:read'],
        ]);
    }

    #[Route('/teams', name: 'app_team_add', methods: ['POST'])]
    #[OA\Tag(name: 'Equipes')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Equipe
            $team = new Team();
            $team->setName($data['name']);

            $em->persist($team);
            $em->flush();

            return $this->json($team, context: [
                'groups' => ['team:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/team/{id}', name: 'app_team_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Equipes')]
    public function update(
        Team $team,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Equipe
            $team->setName($data['name']);

            $em->persist($team);
            $em->flush();

            return $this->json($team, context: [
                'groups' => ['team:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/team/{id}', name: 'app_team_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Equipes')]
    public function delete(Team $team, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($team);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Equipe supprimée'
        ]);
    }
}
