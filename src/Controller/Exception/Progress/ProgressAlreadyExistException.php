<?php

namespace App\Controller\Exception\Progress;

use Symfony\Component\HttpFoundation\Response;

class ProgressAlreadyExistException extends \Exception
{
  public function __construct()
  {
    return parent::__construct(code: Response::HTTP_NO_CONTENT);
  }
}