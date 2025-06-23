<?php

namespace App\Service\Lesson;

use App\Controller\Exception\Lesson\LessonNotFindException;
use App\Repository\LessonRepository;
use App\Utils;
use Symfony\Component\Uid\Uuid;

class GetLessonByIdService
{
  public function __construct(
    private LessonRepository $lessonRepository
  ) {}

  public function execute(Uuid $id): array
  {
    $lesson = $this->lessonRepository->find($id);

    if (!$lesson) {
      throw new LessonNotFindException();
    }

    return [
      'id' => $lesson->getId(),
      'title' => $lesson->getTitle(),
      'content' => $lesson->getContent(),
      'position' => $lesson->getPosition(),
      'course' => Utils::formatCourse($lesson->getCourse()),
      'progress' => Utils::formatProgress($lesson->getProgress())
    ];
  }
}