<?php

namespace App\Controller\Exception\Lesson;

use Symfony\Component\HttpFoundation\Response;

class NoChangesLessonDetectedException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('No changes detected in the lesson data.', Response::HTTP_BAD_REQUEST);
  }
}