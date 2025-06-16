<?php

namespace App\Controller\Exception\Course;

use Symfony\Component\HttpFoundation\Response;

class CourseNotFindException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('course not find', Response::HTTP_NOT_FOUND);
  }
}