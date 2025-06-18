<?php

namespace App\Controller\Api\Lesson;

use App\Controller\DTO\Lesson\EditLessonRequest;
use App\Service\Lesson\EditLessonService;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class EditLessonController extends AbstractController
{
  public function __construct(
    private EditLessonService $editLessonService,
    private ValidationService $validationService,
    private SerializerInterface $serializer,
  ) {}

  #[Route('lesson/{id}', name: 'edit_lesson', methods: ['PUT'])]
  public function editLesson(string $id, Request $request): Response
  {
    $lessonRequest = $this->serializer->deserialize(
      $request->getContent(),
      EditLessonRequest::class,
      'json'
    );

    $errors = $this->validationService->validate($lessonRequest);
    if (!empty($errors)) {
      return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    $id = Uuid::isValid($id) ? Uuid::fromString($id) : null;
    if (!$id instanceof Uuid) {
      return $this->json(['error' => 'invalid lesson id'], Response::HTTP_BAD_REQUEST);
    }

    try {
      $result = $this->editLessonService->execute($id, $lessonRequest);

      return $this->json($result);
    } catch (\Exception $e) {
      return $this->json(['error' => $e->getMessage()], $e->getCode());
    }
  }
}