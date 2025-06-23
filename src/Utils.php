<?php

namespace App;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Entity\Progress;
use App\Entity\User;

class Utils
{
  public static function formatDateTime(\DateTimeInterface $dateTime): string
  {
    return $dateTime->format('Y-m-d H:i:s');
  }

  public static function formatLessons(Lesson | array $lessons): array
  {
    if (!is_array($lessons)) {
      return [
        'id' => $lessons->getId(),
        'title' => $lessons->getTitle(),
        'content' => $lessons->getContent(),
        'position' => $lessons->getPosition(),
      ];
    }

    $lessons = array_map(function ($lesson) {
      return [
        'id' => $lesson->getId(),
        'title' => $lesson->getTitle(),
        'content' => $lesson->getContent(),
        'position' => $lesson->getPosition(),
      ];
    }, $lessons);

    return $lessons;
  }

  public static function formatCourse(Course $course): array
  {
    return [
      'id' => $course->getId(),
      'title' => $course->getTitle(),
      'description' => $course->getDescription(),
      'created_at' => self::formatDateTime($course->getCreatedAt()),
    ];
  }

  public static function formatProgress(Progress | array $progress): array
  {
    if (!is_array($progress)) {
      return [
        'id' => $progress->getId(),
        'user' => $progress->getUser(),
        'completed_at' => self::formatDateTime($progress->getCompletedAt()),
      ];
    }

    $progress = array_map(function ($progress) {
      return [
        'id' => $progress->getId(),
        'user' => self::formatUser($progress->getUser()),
        'completed_at' => self::formatDateTime($progress->getCompletedAt()),
      ];
    }, $progress);

    return $progress;
  }

  public static function formatUser(User $user): array
  {
    return [
      'id' => $user->getId(),
      'name' => $user->getName(),
      'email' => $user->getEmail(),
      'roles' => $user->getRoles(),
    ];
  }
}