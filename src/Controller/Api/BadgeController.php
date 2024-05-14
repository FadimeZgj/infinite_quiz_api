<?php

namespace App\Controller\Api;

use App\Entity\Badge;
use App\Entity\User;
use App\Repository\BadgeRepository;
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
    description: 'Retourne tous les badges',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Badge::class, groups: ['badge:read']))
    )
)]
#[OA\Tag(name: 'Badges')]
#[Security(name: 'Bearer')]
class BadgeController extends AbstractController
{
    #[Route('/badges', name: 'app_badges', methods: ['GET'])]

    public function index(BadgeRepository $badgeRepository): JsonResponse
    {
        $badges = $badgeRepository->findAll();

        return $this->json([
            'badges' => $badges,
        ], context: [
            'groups' => ['badge:read']
        ]);
    }

    #[Route('/badge/{id}', name: 'app_badge_get', methods: ['GET'])]
    #[OA\Tag(name: 'Badges')]
    public function get(Badge $badge): JsonResponse
    {
        return $this->json($badge, context: [
            'groups' => ['badge:read'],
        ]);
    }

    #[Route('/badges', name: 'app_badge_add', methods: ['POST'])]
    #[OA\Tag(name: 'Badges')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouveau Badge
            $badge = new Badge();
            $badge->setName($data['name']);

            $em->persist($badge);
            $em->flush();

            return $this->json($badge, context: [
                'groups' => ['badge:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/badge/{id}', name: 'app_badge_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Badges')]
    public function update(
        Badge $badge,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouveau Badge
            $badge->setName($data['name']);

            // Ajouter les nouveaux utilisateurs associés au badge
            if (isset($data['users']) && is_array($data['users'])) {
                foreach ($data['users'] as $userId) {
                    // Récupérer l'utilisateur à partir de l'ID
                    $user = $em->getRepository(User::class)->find($userId);
                    if ($user) {
                        $badge->addUser($user);
                    }
                }
            }

            $em->persist($badge);
            $em->flush();

            return $this->json($badge, context: [
                'groups' => ['badge:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/badge/{id}', name: 'app_badge_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Badges')]
    public function delete(Badge $badge, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($badge);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Badge supprimé'
        ]);
    }
}
