<?php

namespace App\Controller\Api;

use App\Entity\Role;
use App\Repository\RoleRepository;
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
    description: 'Retourne tous les Roles',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Role::class, groups: ['role:read']))
    )
)]
#[OA\Tag(name: 'Roles')]
#[Security(name: 'Bearer')]

class RoleController extends AbstractController
{
    #[Route('/roles', name: 'app_roles', methods: ['GET'])]
    public function index(RoleRepository $roleRepository): JsonResponse
    {
        $roles = $roleRepository->findAll();

        return $this->json([
            'roles' => $roles,
        ], context: [
            'groups' => ['role:read']
        ]);
    }

    #[Route('/role/{id}', name: 'app_role_get', methods: ['GET'])]
    #[OA\Tag(name: 'Roles')]
    public function get(Role $role): JsonResponse
    {
        return $this->json($role, context: [
            'groups' => ['role:read'],
        ]);
    }

    #[Route('/roles', name: 'app_role_add', methods: ['POST'])]
    #[OA\Tag(name: 'Roles')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouveau Role
            $role = new Role();
            $role->setName($data['name']);

            $em->persist($role);
            $em->flush();

            return $this->json($role, context: [
                'groups' => ['role:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/role/{id}', name: 'app_role_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Roles')]
    public function update(
        Role $role,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouveau role
            $role->setName($data['name']);

            $em->persist($role);
            $em->flush();

            return $this->json($role, context: [
                'groups' => ['role:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/role/{id}', name: 'app_role_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Roles')]
    public function delete(Role $role, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($role);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Role supprimé'
        ]);
    }
}
