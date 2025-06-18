<?php

namespace App\Service\Lesson;

use App\Controller\DTO\Lesson\EditLessonRequest;
use App\Controller\Exception\Course\CourseNotFindException;
use App\Controller\Exception\Lesson\AlreadyExistLessonException;
use App\Controller\Exception\Lesson\LessonNotFindException;
use App\Controller\Exception\Lesson\LessonWithSamePositionException;
use App\Controller\Exception\Lesson\NoChangesLessonDetectedException;
use App\Controller\Exception\Lesson\PositionMustBeNonNegativeIntegerException;
use App\Repository\CourseRepository;
use App\Repository\LessonRepository;
use App\Utils;
use Symfony\Component\Uid\Uuid;

class EditLessonService
{
  public function __construct(
    private LessonRepository $lessonRepository,
    private CourseRepository $courseRepository,
  ) {}

  public function execute(Uuid $lessonId, EditLessonRequest $lessonData): array
  {
    $lesson = $this->lessonRepository->find($lessonId);

    if (!$lesson) {
      throw new LessonNotFindException();
    }

    $fields = ['title', 'content', 'position', 'course'];
    $changed = false;

    foreach ($fields as $field) {
      $getter = 'get' . ucfirst($field);

      if ($field === 'course') {
        $newCourseId = Uuid::fromString($lessonData->$getter());
        $oldCourseId = $lesson->getCourse()->getId();

        if ($newCourseId != $oldCourseId) {
          $changed = true;
          break;
        }

        continue;
      }

      if ($lessonData->$getter() != $lesson->$getter()) {
        $changed = true;
        break;
      }
    }

    if (!$changed) {
      throw new NoChangesLessonDetectedException();
    }

    if (
      $this->lessonRepository->findOneBy(['title' => $lessonData->getTitle()]) &&
      $lesson->getTitle() !== $lessonData->getTitle()
    ) {
      throw new AlreadyExistLessonException();
    }

    if ($lessonData->getPosition() < 0) {
      throw new PositionMustBeNonNegativeIntegerException();
    }

    if ($lesson->getPosition() !== $lessonData->getPosition()) {
      if (
        $lesson->getCourse()
        ->getLessons()
        ->exists(
          fn($_, $existingLesson) =>
          $existingLesson->getPosition() === $lessonData->getPosition() &&
            $existingLesson->getId() !== $lesson->getId()
        )
      ) {
        throw new LessonWithSamePositionException();
      }
    }

    if (
      !$this->courseRepository->find($lessonData->getCourse()) ||
      !Uuid::isValid($lessonData->getCourse())
    ) {
      throw new CourseNotFindException();
    }

    $lesson->setTitle($lessonData->getTitle());
    $lesson->setContent($lessonData->getContent());
    $lesson->setPosition($lessonData->getPosition());
    $lesson->setCourse($this->courseRepository->find($lessonData->getCourse()));

    $this->lessonRepository->save($lesson);

    return [
      'id' => $lesson->getId(),
      'title' => $lesson->getTitle(),
      'content' => $lesson->getContent(),
      'position' => $lesson->getPosition(),
      'course' => Utils::formatCourse($lesson->getCourse()),
      'progress' => $lesson->getProgress(), // Colocar depois: Utils::formatProgress($lesson->getProgress())
    ];
  }
}