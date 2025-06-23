<?php

namespace App\Entity;

use App\Repository\ProgressRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProgressRepository::class)]
class Progress
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: "CUSTOM")]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    #[Groups('progress')]
    private ?Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'progress')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('progress')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'progress')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups('progress')]
    private ?Lesson $lesson = null;

    #[ORM\Column]
    #[Groups('progress')]
    private ?\DateTimeImmutable $completed_at = null;

    public function __construct()
    {
        $this->completed_at = new DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getLesson(): ?Lesson
    {
        return $this->lesson;
    }

    public function setLesson(?Lesson $lesson): static
    {
        $this->lesson = $lesson;

        return $this;
    }

    public function getCompletedAt(): ?\DateTimeImmutable
    {
        return $this->completed_at;
    }

    public function setCompletedAt(\DateTimeImmutable $completed_at): static
    {
        $this->completed_at = $completed_at;

        return $this;
    }
}