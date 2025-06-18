<?php

namespace App\Controller\DTO\Lesson;

use Symfony\Component\Validator\Constraints as Assert;

class CreateLessonRequest
{
  #[Assert\NotBlank]
  #[Assert\Length(min: 3, max: 255)]
  private string $title;

  #[Assert\NotBlank]
  #[Assert\Length(min: 10, max: 1000)]
  private string $content;

  #[Assert\NotBlank]
  #[Assert\Range(min: 1)]
  private int $position;

  #[Assert\NotBlank]
  #[Assert\Uuid]
  private string $courseId;

  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function setContent(string $content): void
  {
    $this->content = $content;
  }

  public function getPosition(): int
  {
    return $this->position;
  }

  public function setPosition(int $position): void
  {
    $this->position = $position;
  }

  public function getCourseId(): string
  {
    return $this->courseId;
  }

  public function setCourseId(string $courseId): void
  {
    $this->courseId = $courseId;
  }
}