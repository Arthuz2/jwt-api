<?php

namespace App\Service\Course;

use App\Repository\CourseRepository;
use App\Utils;
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
    if (!$courses) {
      return [];
    }

    $courses = $this->paginator->paginate($courses, $page, 10);

    $numberOfPages = ceil($courses->getTotalItemCount() / $courses->getItemNumberPerPage());
    if ($page > $numberOfPages) {
      throw new \OutOfBoundsException('Page number exceeds total pages available.', Response::HTTP_BAD_REQUEST);
    }

    foreach ($courses as $course) {
      $lessons = array_map(function ($lesson) {
        return [
          'id' => $lesson->getId(),
          'title' => $lesson->getTitle(),
          'content' => $lesson->getContent(),
          'position' => $lesson->getPosition(),
        ];
      }, $course->getLessons()->toArray());
    }

    $data = array_map(function ($course) {
      return [
        'id' => $course->getId(),
        'title' => $course->getTitle(),
        'description' => $course->getDescription(),
        'created_at' => Utils::formatDateTime($course->getCreatedAt()),
        'lessons' => Utils::formatLessons($course->getLessons()->toArray()),
      ];
    }, $courses->getItems());

    return [
      'total' => $courses->getTotalItemCount(),
      'page' => $page,
      'pages' => $numberOfPages,
      'limitPerPage' => $courses->getItemNumberPerPage(),
      'courses' => $data,
    ];
  }
}