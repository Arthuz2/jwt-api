<?php

namespace App\Controller\Api\Course;

use App\Controller\DTO\Course\CreateCourseRequest;
use App\Service\Course\CreateCourseService;
use App\Service\ValidationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CreateCourseController extends AbstractController
{
  public function __construct(
    private CreateCourseService $createCourseService,
    private ValidationService $validationService,
    private SerializerInterface $serializer,
  ) {}

  #[Route('/course', name: 'api_course_create', methods: ['POST'])]
  public function createCourse(Request $request): JsonResponse
  {
    $courseRequest = $this->serializer->deserialize(
      $request->getContent(),
      CreateCourseRequest::class,
      'json'
    );

    $errors = $this->validationService->validate($courseRequest);
    if (!empty($errors)) {
      return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
    }

    try {
      $result = $this->createCourseService->execute(
        title: $courseRequest->getTitle(),
        description: $courseRequest->getDescription(),
      );

      return $this->json($result, Response::HTTP_CREATED);
    } catch (\Exception $e) {
      return $this->json(['error' => $e->getMessage()], status: $e->getCode());
    }
  }
}