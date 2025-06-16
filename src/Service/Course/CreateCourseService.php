<?php

namespace App\Service\Course;

use App\Controller\Exception\Course\AlreadyExistCourseException;
use App\Entity\Course;
use App\Repository\CourseRepository;

class CreateCourseService
{
  public function __construct(
    private CourseRepository $courseRepository,
  ) {}

  public function execute(string $title, string $description): array
  {
    $course = new Course();
    $course->setTitle($title);
    $course->setDescription($description);

    if ($this->courseRepository->findOneBy(['title' => $title])) {
      throw new AlreadyExistCourseException();
    }

    $this->courseRepository->save($course);
    return [
      'id' => $course->getId(),
      'title' => $course->getTitle(),
      'description' => $course->getDescription(),
      'created_at' => $course->getCreatedAt()->format('Y-m-d H:i:s'),
    ];
  }
}