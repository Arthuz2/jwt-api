<?php

namespace App\Controller\Exception\Course;

use Symfony\Component\HttpFoundation\Response;

class AlreadyExistCourseException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('already exists this course', Response::HTTP_CONFLICT);
  }
}