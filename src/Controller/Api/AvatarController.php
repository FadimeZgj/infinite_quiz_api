<?php

namespace App\Controller\Api;

use App\Entity\Avatar;
use App\Repository\AvatarRepository;
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
    description: 'Retourne tous les avatars',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Avatar::class, groups: ['avatar:read']))
    )
)]
#[OA\Tag(name: 'Avatars')]
#[Security(name: 'Bearer')]
class AvatarController extends AbstractController
{
    #[Route('/avatars', name: 'app_avatars', methods: ['GET'])]

    public function index(AvatarRepository $avatarRepository): JsonResponse
    {
        $avatars = $avatarRepository->findAll();

        return $this->json([
            'avatars' => $avatars,
        ], context: [
            'groups' => ['avatar:read']
        ]);
    }

    #[Route('/avatar/{id}', name: 'app_avatar_get', methods: ['GET'])]
    #[OA\Tag(name: 'Avatars')]
    public function get(Avatar $avatar): JsonResponse
    {
        return $this->json($avatar, context: [
            'groups' => ['avatar:read'],
        ]);
    }

    #[Route('/avatars', name: 'app_avatar_add', methods: ['POST'])]
    #[OA\Tag(name: 'Avatars')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouvel Avatar
            $avatar = new Avatar();
            $avatar->setName($data['name']);

            $em->persist($avatar);
            $em->flush();

            return $this->json($avatar, context: [
                'groups' => ['avatar:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/avatar/{id}', name: 'app_avatar_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Avatars')]
    public function update(
        Avatar $avatar,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouvel Avatar
            $avatar->setName($data['name']);

            $em->persist($avatar);
            $em->flush();

            return $this->json($avatar, context: [
                'groups' => ['avatar:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/avatar/{id}', name: 'app_avatar_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Avatars')]
    public function delete(Avatar $avatar, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($avatar);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Avatar supprimé'
        ]);
    }
}
