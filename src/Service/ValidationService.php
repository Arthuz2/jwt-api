<?php

namespace App\Service;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationService
{
  public function __construct(
    private ValidatorInterface $validator,
  ) {}

  public function validate(object $dto): array
  {
    $errors = $this->validator->validate($dto);

    if (count($errors) === 0) {
      return [];
    }

    return $this->formatErrors($errors);
  }

  public function formatErrors(ConstraintViolationListInterface $errors): array
  {
    $result = [];
    foreach ($errors as $error) {
      $result[$error->getPropertyPath()] = $error->getMessage();
    }

    return $result;
  }
}
