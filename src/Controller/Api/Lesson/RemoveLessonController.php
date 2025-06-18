<?php

namespace App\Controller\Api\Lesson;

use App\Service\Lesson\RemoveLessonService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class RemoveLessonController extends AbstractController
{
  public function __construct(
    private readonly RemoveLessonService $removeLessonService
  ) {}

  #[Route('/lessons/{id}', name: 'api_lesson_remove', methods: ['DELETE'])]
  public function removeLesson(string $id): JsonResponse
  {
    $id = Uuid::isValid($id) ? Uuid::fromString($id) : null;
    if (!$id) {
      return $this->json(['error' => 'Invalid lesson ID'], Response::HTTP_BAD_REQUEST);
    }

    try {
      $result = $this->removeLessonService->execute($id);

      return $this->json($result);
    } catch (\Exception $e) {
      return $this->json(['error' => $e->getMessage()], $e->getCode());
    }
  }
}