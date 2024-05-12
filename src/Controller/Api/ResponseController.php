<?php

namespace App\Controller\Api;

use App\Entity\Response;
use App\Repository\ResponseRepository;
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
    description: 'Retourne toutes les Réponses',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Response::class, groups: ['response:read']))
    )
)]
#[OA\Tag(name: 'Réponses')]
#[Security(name: 'Bearer')]

class ResponseController extends AbstractController
{
    #[Route('/responses', name: 'app_responses', methods: ['GET'])]
    public function index(ResponseRepository $responseRepository): JsonResponse
    {
        $responses = $responseRepository->findAll();

        return $this->json([
            'responses' => $responses,
        ], context: [
            'groups' => ['response:read']
        ]);
    }

    #[Route('/response/{id}', name: 'app_response_get', methods: ['GET'])]
    #[OA\Tag(name: 'Réponses')]
    public function get(Response $response): JsonResponse
    {
        return $this->json($response, context: [
            'groups' => ['response:read'],
        ]);
    }

    #[Route('/responses', name: 'app_response_add', methods: ['POST'])]
    #[OA\Tag(name: 'Réponses')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Réponse
            $response = new Response();
            $response->setResponse($data['response']);
            $response->setCorrect($data['is_correct']);

            $em->persist($response);
            $em->flush();

            return $this->json($response, context: [
                'groups' => ['response:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/response/{id}', name: 'app_response_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Réponses')]
    public function update(
        Response $response,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Equipe
            $response->setResponse($data['response']);

            $em->persist($response);
            $em->flush();

            return $this->json($response, context: [
                'groups' => ['response:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/response/{id}', name: 'app_response_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Réponses')]
    public function delete(Response $response, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($response);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Réponse supprimée'
        ]);
    }
}
