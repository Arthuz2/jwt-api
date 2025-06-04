<?php

namespace App\Controller\DTO\Auth;

use Symfony\Component\Validator\Constraints as Assert;

class RegisterRequest
{
  #[Assert\NotBlank]
  private string $name;

  #[Assert\NotBlank]
  #[Assert\Email]
  private string $email;

  #[Assert\NotBlank]
  private string $password;

  public function getName(): string
  {
    return $this->name;
  }

  public function getEmail(): string
  {
    return $this->email;
  }

  public function getPassword(): string
  {
    return $this->password;
  }

  public function setName(string $name): static
  {
    $this->name = $name;

    return $this;
  }

  public function setEmail(string $email): static
  {
    $this->email = $email;

    return $this;
  }

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }
}
