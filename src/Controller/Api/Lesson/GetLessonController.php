<?php

namespace App\Controller\Api\Lesson;

use App\Service\Lesson\GetAllLessonsService;
use App\Service\Lesson\GetLessonByIdService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

final class GetLessonController extends AbstractController
{
    public function __construct(
        private GetAllLessonsService $getAllLessonsService,
        private GetLessonByIdService $getLessonByIdService,
        private SerializerInterface $serializer,
    ) {}

    #[Route('/lessons', name: 'api_lessons_list', methods: ['GET'])]
    public function getAllLessons(Request $request): JsonResponse
    {
        $page = (int) $request->query->getInt('page', 1);

        try {
            $result = $this->getAllLessonsService->execute($page);

            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    #[Route('/lesson/{id}', name: 'api_lesson_by_id', methods: ['GET'])]
    public function getLessonById(string $id): JsonResponse
    {
        $id = Uuid::isValid($id) ? Uuid::fromString($id) : null;
        if (!$id instanceof Uuid) {
            return $this->json(['error' => 'invalid id format'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->getLessonByIdService->execute($id);

            return $this->json($result, context: ['groups' => 'lesson']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}