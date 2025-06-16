<?php

namespace App\Controller\Exception;

use Symfony\Component\HttpFoundation\Response;

class InvalidCredentialsException extends \Exception
{
  public function __construct()
  {
    return parent::__construct('invalid credentials', Response::HTTP_UNAUTHORIZED);
  }
}