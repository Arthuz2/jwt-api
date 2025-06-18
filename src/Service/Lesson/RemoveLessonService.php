<?php

namespace App\Service\Lesson;

use App\Controller\Exception\Lesson\LessonNotFindException;
use App\Repository\LessonRepository;
use Symfony\Component\Uid\Uuid;

class RemoveLessonService
{
  public function __construct(
    private readonly LessonRepository $lessonRepository
  ) {}

  public function execute(Uuid $id): array
  {
    $lesson = $this->lessonRepository->find($id);
    if (!$lesson) {
      throw new LessonNotFindException();
    }

    $this->lessonRepository->remove($lesson);

    return ['message' => 'Lesson removed successfully'];
  }
}