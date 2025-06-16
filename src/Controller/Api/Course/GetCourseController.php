<?php

namespace App\Controller\Api\Course;

use App\Service\Course\GetAllCoursesService;
use App\Service\Course\GetCourseByIdService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

final class GetCourseController extends AbstractController
{
    public function __construct(
        private GetAllCoursesService $getAllCoursesService,
        private GetCourseByIdService $getCourseByIdService,
    ) {}

    #[Route('/courses', name: 'api_courses_list', methods: ['GET'])]
    public function getAllCourses(Request $request): JsonResponse
    {
        $page = (int) $request->query->getInt('page', 1);

        try {
            $result = $this->getAllCoursesService->execute($page);

            return $this->json($result);
        } catch (\Throwable $th) {
            return $this->json(['error' => $th->getMessage()], $th->getCode());
        }
    }

    #[Route('/course/{id}', name: 'api_course_by_id', methods: ['GET'])]
    public function getCourseById(string $id): JsonResponse
    {
        $id = Uuid::isValid($id) ? Uuid::fromString($id) : null;
        if (!$id instanceof Uuid) {
            return $this->json(['error' => 'invalid id format'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->getCourseByIdService->execute($id);

            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}