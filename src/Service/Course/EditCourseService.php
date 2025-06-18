<?php

namespace App\Service\Course;

use App\Controller\Exception\Course\AlreadyExistCourseException;
use App\Controller\Exception\Course\CourseNotFindException;
use App\Repository\CourseRepository;
use App\Utils;
use Symfony\Component\Uid\Uuid;

class EditCourseService
{
  public function __construct(
    private CourseRepository $courseRepository,
  ) {}

  public function execute(Uuid $courseId, array $courseData): array
  {
    $course = $this->courseRepository->find($courseId);

    if (!$course) {
      throw new CourseNotFindException();
    }

    if (
      $this->courseRepository->findOneBy(['title' => $courseData['title']]) &&
      $course->getTitle() !== $courseData['title']
    ) {
      throw new AlreadyExistCourseException();
    }

    $course->setTitle($courseData['title']);
    $course->setDescription($courseData['description']);

    $this->courseRepository->save($course);

    return [
      'id' => $course->getId(),
      'title' => $course->getTitle(),
      'description' => $course->getDescription(),
      'lessons' => Utils::formatLessons($course->getLessons()->toArray()),
      'createdAt' => Utils::formatDateTime($course->getCreatedAt()),
    ];
  }
}