<?php

namespace App\Service\Course;

use App\Controller\Exception\Course\CourseNotFindException;
use App\Repository\CourseRepository;
use App\Utils;
use Symfony\Component\Uid\Uuid;

class GetCourseByIdService
{
  public function __construct(
    private CourseRepository $courseRepository
  ) {}

  public function execute(Uuid $id): array
  {
    $course = $this->courseRepository->find($id);

    if (!$course) {
      throw new CourseNotFindException();
    }

    return [
      'id' => $course->getId(),
      'title' => $course->getTitle(),
      'description' => $course->getDescription(),
      'lessons' => Utils::formatLessons($course->getLessons()->toArray()),
      'created_at' => Utils::formatDateTime($course->getCreatedAt()),
    ];
  }
}