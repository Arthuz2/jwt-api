<?php

namespace App\Controller\DTO\Course;

use Symfony\Component\Validator\Constraints as Assert;

class EditCourseRequest
{
  #[Assert\NotBlank]
  #[Assert\Length(min: 3, max: 255)]
  private string $title;

  #[Assert\NotBlank]
  #[Assert\Length(min: 10, max: 1000)]
  private string $description;

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getDescription(): string
  {
    return $this->description;
  }

  public function setDescription(string $description): void
  {
    $this->description = $description;
  }

  public function toArray(): array
  {
    return [
      'title' => $this->getTitle(),
      'description' => $this->getDescription(),
    ];
  }
}