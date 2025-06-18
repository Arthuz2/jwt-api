<?php

namespace App\Controller\Api\Lesson;

use App\Controller\DTO\Lesson\CreateLessonRequest;
use App\Service\Lesson\CreateLessonService;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class CreateLessonController extends AbstractController
{
  public function __construct(
    private CreateLessonService $createLessonService,
    private ValidationService $validationService,
    private SerializerInterface $serializer,
  ) {}

  #[Route('/lessons', name: 'api_lesson_create', methods: ['POST'])]
  public function createLesson(Request $request): JsonResponse
  {
    $lessonRequest = $this->serializer->deserialize(
      $request->getContent(),
      CreateLessonRequest::class,
      'json'
    );

    $errors = $this->validationService->validate($lessonRequest);
    if (!empty($errors)) {
      return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
    }

    try {
      $result = $this->createLessonService->execute(
        title: $lessonRequest->getTitle(),
        content: $lessonRequest->getContent(),
        position: $lessonRequest->getPosition(),
        courseId: Uuid::fromString($lessonRequest->getCourseId())
      );

      return $this->json($result, Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return $this->json(['error' => $e->getMessage()], $e->getCode());
    }
  }
}