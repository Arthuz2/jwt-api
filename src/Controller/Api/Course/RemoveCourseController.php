<?php

namespace App\Controller\Api\Course;

use App\Service\Course\RemoveCourseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class RemoveCourseController extends AbstractController
{
  public function __construct(
    private readonly RemoveCourseService $removeCourseService
  ) {}

  #[Route('/courses/{id}', name: 'api_course_remove', methods: ['DELETE'])]
  public function removeCourse(string $id): JsonResponse
  {
    $id = Uuid::isValid($id) ? Uuid::fromString($id) : null;
    if (!$id) {
      return $this->json(['error' => 'Invalid course ID'], Response::HTTP_BAD_REQUEST);
    }

    try {
      $result = $this->removeCourseService->execute($id);

      return $this->json($result);
    } catch (\Exception $e) {
      return $this->json(['error' => $e->getMessage()], $e->getCode());
    }
  }
}