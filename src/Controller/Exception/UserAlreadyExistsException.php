<?php

namespace App\Controller\Exception;

use Symfony\Component\HttpFoundation\Response;

class UserAlreadyExistsException extends \Exception
{
  public function __construct()
  {
    parent::__construct('user already exists with this email', Response::HTTP_CONFLICT);
  }
}
