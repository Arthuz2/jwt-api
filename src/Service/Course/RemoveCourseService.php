<?php

namespace App\Service\Course;

use App\Controller\Exception\Course\CourseNotFindException;
use App\Repository\CourseRepository;
use Symfony\Component\Uid\Uuid;

class RemoveCourseService
{
  public function __construct(
    private readonly CourseRepository $courseRepository,
  ) {}

  public function execute(Uuid $id): array
  {
    $course = $this->courseRepository->find($id);
    if (!$course) {
      throw new CourseNotFindException();
    }

    $this->courseRepository->remove($course);

    return ['message' => 'Course removed successfully'];
  }
}