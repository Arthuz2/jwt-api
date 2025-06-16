<?php

namespace App\Service\Course;

use App\Repository\CourseRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;

class GetAllCoursesService
{
  public function __construct(
    private CourseRepository $courseRepository,
    private PaginatorInterface $paginator,
  ) {}

  public function execute(int $page): array
  {
    $courses = $this->courseRepository->findAll();

    $courses = $this->paginator->paginate($courses, $page, 10);

    $numberOfPages = ceil($courses->getTotalItemCount() / $courses->getItemNumberPerPage());
    if ($page > $numberOfPages) {
      throw new \OutOfBoundsException('Page number exceeds total pages available.', Response::HTTP_BAD_REQUEST);
    }

    $data = array_map(function ($course) {
      return [
        'id' => $course->getId(),
        'title' => $course->getTitle(),
        'description' => $course->getDescription(),
        'created_at' => $course->getCreatedAt()->format('Y-m-d H:i:s'),
      ];
    }, $courses->getItems());

    return [
      'courses' => $data,
      'total' => $courses->getTotalItemCount(),
      'page' => $page,
      'pages' => $numberOfPages,
      'limit' => $courses->getItemNumberPerPage(),
    ];
  }
}