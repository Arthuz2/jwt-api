<?php

namespace App\Controller\Exception\Lesson;

use Symfony\Component\HttpFoundation\Response;

class LessonNotFindException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('lesson not find', Response::HTTP_NOT_FOUND);
  }
}