<?php

namespace App;

use App\Entity\Course;

class Utils
{
  public static function formatDateTime(\DateTimeInterface $dateTime): string
  {
    return $dateTime->format('Y-m-d H:i:s');
  }

  public static function formatLessons($lessons): array
  {
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
}