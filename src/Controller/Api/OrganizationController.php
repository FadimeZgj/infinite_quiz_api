<?php

namespace App\Controller\Api;

use App\Entity\Organization;
use App\Repository\OrganizationRepository;
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
    description: 'Retourne toutes les structures',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Organization::class, groups: ['organization:read']))
    )
)]
#[OA\Tag(name: 'Structures')]
#[Security(name: 'Bearer')]
class OrganizationController extends AbstractController
{
    #[Route('/organizations', name: 'app_organizations', methods: ['GET'])]

    public function index(OrganizationRepository $organizationRepository): JsonResponse
    {
        $organizations = $organizationRepository->findAll();

        return $this->json([
            'organizations' => $organizations,
        ], context: [
            'groups' => ['organization:read']
        ]);
    }

    #[Route('/organization/{id}', name: 'app_organization_get', methods: ['GET'])]
    #[OA\Tag(name: 'Structures')]
    public function get(Organization $organization): JsonResponse
    {
        return $this->json($organization, context: [
            'groups' => ['organization:read'],
        ]);
    }

    #[Route('/organizations', name: 'app_organization_add', methods: ['POST'])]
    #[OA\Tag(name: 'Structures')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle structure
            $organization = new Organization();
            $organization->setName($data['name']);
            $organization->setCountry($data['country']);

            $em->persist($organization);
            $em->flush();

            return $this->json($organization, context: [
                'groups' => ['organization:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/organization/{id}', name: 'app_organization_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Structures')]
    public function update(
        Organization $organization,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Structure
            $organization->setName($data['name']);
            $organization->setCountry($data['country']);

            $em->persist($organization);
            $em->flush();

            return $this->json($organization, context: [
                'groups' => ['organization:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/organization/{id}', name: 'app_Organization_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Structures')]
    public function delete(Organization $organization, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($organization);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Structure supprimée'
        ]);
    }
}
