<?php

namespace App\Service\Lesson;

use App\Controller\Exception\Course\CourseNotFindException;
use App\Controller\Exception\Lesson\AlreadyExistLessonException;
use App\Controller\Exception\Lesson\LessonWithSamePositionException;
use App\Controller\Exception\Lesson\PositionMustBeNonNegativeIntegerException;
use App\Entity\Lesson;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Utils;
use Symfony\Component\Uid\Uuid;

class CreateLessonService
{
  public function __construct(
    private LessonRepository $lessonRepository,
    private CourseRepository $courseRepository,
  ) {}

  public function execute(string $title, string $content, int $position, Uuid $courseId): array
  {
    if ($this->lessonRepository->findOneBy(['title' => $title, 'course' => $courseId])) {
      throw new AlreadyExistLessonException();
    }

    if ($position <= 0) {
      throw new PositionMustBeNonNegativeIntegerException();
    }

    if (!$this->courseRepository->find($courseId)) {
      throw new CourseNotFindException();
    }

    if (
      $this->courseRepository->find($courseId)
      ->getLessons()
      ->exists(
        fn($_, $existingLesson) => $existingLesson->getPosition() === $position
      )
    ) {
      throw new LessonWithSamePositionException();
    }

    if (
      !$this->courseRepository->find($courseId) ||
      !Uuid::isValid($courseId)
    ) {
      throw new CourseNotFindException();
    }

    $lesson = new Lesson();
    $lesson->setTitle($title);
    $lesson->setContent($content);
    $lesson->setPosition($position);
    $lesson->setCourse($this->courseRepository->find($courseId));

    $this->lessonRepository->save($lesson);
    return [
      'id' => $lesson->getId(),
      'title' => $lesson->getTitle(),
      'content' => $lesson->getContent(),
      'position' => $lesson->getPosition(),
      'course' => Utils::formatCourse($lesson->getCourse()),
    ];
  }
}