<?php

namespace App\Service\Course;

use App\Repository\CourseRepository;

class GetAllCoursesService
{
  public function __construct(
    private CourseRepository $courseRepository,
  ) {}

  public function execute(): array
  {
    $courses = $this->courseRepository->findAll();

    $data = array_map(function ($course) {
      return [
        'id' => $course->getId(),
        'title' => $course->getTitle(),
        'description' => $course->getDescription(),
        'created_at' => $course->getCreatedAt()->format('Y-m-d H:i:s'),
      ];
    }, $courses);

    return $data;
  }
}
