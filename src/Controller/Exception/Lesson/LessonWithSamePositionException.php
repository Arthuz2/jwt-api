<?php

namespace App\Controller\Exception\Lesson;

use Symfony\Component\HttpFoundation\Response;

class LessonWithSamePositionException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('A lesson with this position already exists in the course.', Response::HTTP_CONFLICT);
  }
}