<?php

namespace App\Controller\Exception\Lesson;

use Symfony\Component\HttpFoundation\Response;

class AlreadyExistLessonException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('already exists this lesson', Response::HTTP_CONFLICT);
  }
}