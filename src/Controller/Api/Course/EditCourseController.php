<?php

namespace App\Controller\Api\Course;

use App\Controller\DTO\Course\EditCourseRequest;
use App\Service\Course\EditCourseService;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Uuid;

class EditCourseController extends AbstractController
{
  public function __construct(
    private EditCourseService $editCourseService,
    private ValidationService $validationService,
    private SerializerInterface $serializer,
  ) {}

  #[Route('course/{id}', name: 'edit_course', methods: ['PUT'])]
  public function editCourse(string $id, Request $request): Response
  {
    $courseRequest = $this->serializer->deserialize(
      $request->getContent(),
      EditCourseRequest::class,
      'json'
    );

    $errors = $this->validationService->validate($courseRequest);
    if (!empty($errors)) {
      return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
    }

    $id = Uuid::isValid($id) ? Uuid::fromString($id) : null;
    if (!$id instanceof Uuid) {
      return $this->json(['error' => 'invalid course id'], Response::HTTP_BAD_REQUEST);
    }

    try {
      $result = $this->editCourseService->execute($id, $courseRequest->toArray());

      return $this->json($result);
    } catch (\Exception $e) {
      return $this->json(['error' => $e->getMessage()], $e->getCode());
    }
  }
}