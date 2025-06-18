<?php

namespace App\Controller\Exception\Lesson;

use Symfony\Component\HttpFoundation\Response;

class PositionMustBeNonNegativeIntegerException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('Position must be a non-negative integer.', Response::HTTP_BAD_REQUEST);
  }
}