<?php

namespace App\Service\Progress;

use App\Controller\Exception\Lesson\LessonNotFindException;
use App\Controller\Exception\Progress\ProgressAlreadyExistException;
use App\Entity\Progress;
use App\Entity\User;
use App\Repository\LessonRepository;
use App\Repository\ProgressRepository;
use App\Utils;
use Symfony\Component\Uid\Uuid;

class MarkLessonAsCompletedService
{
  public function __construct(
    private LessonRepository $lessonRepository,
    private ProgressRepository $progressRepository,
  ) {}

  public function execute(User $user, Uuid $lessonId): array
  {
    $lesson = $this->lessonRepository->find($lessonId);
    if (!$lesson) {
      throw new LessonNotFindException();
    }

    $progress = $this->progressRepository->findOneBy(['lesson' => $lesson, 'user' => $user]);
    if ($progress) {
      throw new ProgressAlreadyExistException();
    }

    $progress = new Progress();
    $progress->setLesson($lesson);
    $progress->setUser($user);

    $this->progressRepository->save($progress);

    return [
      'id' => $progress->getId(),
      'user' => Utils::formatUser($progress->getUser()),
      'lesson' => Utils::formatLessons($progress->getLesson()),
      'completed_at' => Utils::formatDateTime($progress->getCompletedAt()),
    ];
  }
}