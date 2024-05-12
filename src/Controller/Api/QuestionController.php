<?php

namespace App\Controller\Api;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Repository\QuestionRepository;
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
    description: 'Retourne toutes les Questions',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Question::class, groups: ['question:read']))
    )
)]
#[OA\Tag(name: 'Questions')]
#[Security(name: 'Bearer')]

class QuestionController extends AbstractController
{
    #[Route('/questions', name: 'app_questions', methods: ['GET'])]
    public function index(QuestionRepository $questionRepository): JsonResponse
    {
        $questions = $questionRepository->findAll();

        return $this->json([
            'questions' => $questions,
        ], context: [
            'groups' => ['question:read']
        ]);
    }

    #[Route('/question/{id}', name: 'app_question_get', methods: ['GET'])]
    #[OA\Tag(name: 'Questions')]
    public function get(Question $question): JsonResponse
    {
        return $this->json($question, context: [
            'groups' => ['question:read'],
        ]);
    }

    #[Route('/questions', name: 'app_question_add', methods: ['POST'])]
    #[OA\Tag(name: 'Questions')]
    public function add(
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {

            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Question
            $question = new Question();
            $question->setQuestion($data['question']);

            // Récupérer le quiz associé à partir de l'ID
            $quiz = $em->getRepository(Quiz::class)->find($data['quiz_id']);
            if (!$quiz) {
                throw new \Exception('Quiz not found', 404);
            }

            // Associer la question au quiz
            $question->setQuiz($quiz);

            $em->persist($question);
            $em->flush();

            return $this->json($question, context: [
                'groups' => ['question:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/question/{id}', name: 'app_question_update', methods: ['PUT', 'PATCH'])]
    #[OA\Tag(name: 'Questions')]
    public function update(
        Question $question,
        Request $request,
        EntityManagerInterface $em,
    ): JsonResponse {
        try {
            // On recupère les données du corps de la requête
            // Que l'on transforme ensuite en tableau associatif
            $data = json_decode($request->getContent(), true);

            // On traite les données pour créer une nouvelle Equipe
            $question->setQuestion($data['question']);

            $em->persist($question);
            $em->flush();

            return $this->json($question, context: [
                'groups' => ['question:read'],
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    #[Route('/question/{id}', name: 'app_question_delete', methods: ['DELETE'])]
    #[OA\Tag(name: 'Questions')]
    public function delete(Question $question, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($question);
        $em->flush();

        return $this->json([
            'code' => 200,
            'message' => 'Question  supprimée'
        ]);
    }
}
