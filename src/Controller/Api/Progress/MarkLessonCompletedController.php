<?php

namespace App\Controller\Api\Progress;

use App\Service\Progress\MarkLessonAsCompletedService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class MarkLessonCompletedController extends AbstractController
{
    public function __construct(
        private MarkLessonAsCompletedService $markLessonAsCompletedService,
    ) {}

    #[Route('/lessons/{lessonId}/progress', name: 'mark_lesson_completed', methods: ['POST'])]
    public function index(string $lessonId): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], Response::HTTP_UNAUTHORIZED);
        }

        $lessonId = Uuid::isValid($lessonId) ? Uuid::fromString($lessonId) : null;
        if (!$lessonId instanceof Uuid) {
            return $this->json(['error' => 'invalid lesson id'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->markLessonAsCompletedService->execute($user, $lessonId);

            return $this->json($result, context: ['groups' => 'progress']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}