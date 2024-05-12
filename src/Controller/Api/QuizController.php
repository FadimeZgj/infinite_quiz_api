<?php

namespace App\Controller\Api;

use App\Entity\Quiz;
use OpenApi\Attributes as OA;
use App\Repository\QuizRepository;
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
    description: 'Retourne tous les quizzes',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Quiz::class, groups: ['quiz:read']))
    )
)]
#[OA\Tag(name: 'Quizzes')]
#[Security(name: 'Bearer')]
class QuizController extends AbstractController
{
    #[Route('/quizzes', name: 'app_quizzes', methods: ['GET'])]

    public function index(QuizRepository $quizRepository): JsonResponse
    {
        $quizzes = $quizRepository->findAll();

        return $this->json([
            'quizzes' => $quizzes,
        ], context: [
            'groups' => ['quiz:read']
        ]);
    }

    #[Route('/quiz/{id}', name: 'app_quiz_get', methods: ['GET'])]
    #[OA\Tag(name: 'Quizzes')]
    public function get(Quiz $quiz): JsonResponse
    {
        return $this->json($quiz, context: [
            'groups' => ['quiz:read'],
        ]);
    }

    #[Route('/quizzes', name: 'app_quiz_add', methods: ['POST'])]
    #[OA\Tag(name: 'Quizzes')]
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

            // On traite les données pour créer un nouveau Quiz
            $quiz = new Quiz();
            $quiz->setTitle($data['title']);
            $quiz->setGroup($data['is_group']);

            // Associez l'ID de l'utilisateur actuel au quiz
            $quiz->setUsers($user);

            $em->persist($quiz);
            $em->flush();

            return $this->json($quiz, context: [
                'groups' => ['quiz:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/quiz/{id}', name: 'app_quiz_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Quizzes')]
    public function update(
        Quiz $quiz,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer un nouveau Quiz
            $quiz->setTitle($data['title']);
            $quiz->setGroup($data['is_group']);

            $em->persist($quiz);
            $em->flush();

            return $this->json($quiz, context: [
                'groups' => ['quiz:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/quiz/{id}', name: 'app_quiz_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Quizzes')]
    public function delete(Quiz $quiz, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($quiz);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Quiz supprimé'
        ]);
    }
}
