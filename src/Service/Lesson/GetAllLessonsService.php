<?php

namespace App\Service\Lesson;

use App\Repository\LessonRepository;
use App\Utils;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;

class GetAllLessonsService
{
  public function __construct(
    private LessonRepository $courseRepository,
    private PaginatorInterface $paginator,
  ) {}

  public function execute(int $page): array
  {
    $lesson = $this->courseRepository->findAll();

    $lesson = $this->paginator->paginate($lesson, $page, 10);

    $numberOfPages = ceil($lesson->getTotalItemCount() / $lesson->getItemNumberPerPage());
    if ($page > $numberOfPages) {
      throw new \OutOfBoundsException('Page number exceeds total pages available.', Response::HTTP_BAD_REQUEST);
    }

    $data = array_map(function ($lesson) {
      return [
        'id' => $lesson->getId(),
        'title' => $lesson->getTitle(),
        'content' => $lesson->getContent(),
        'position' => $lesson->getPosition(),
        'course' => Utils::formatCourse($lesson->getCourse()),
        'progress' => Utils::formatProgress($lesson->getProgress()->getValues())
      ];
    }, $lesson->getItems());

    return [
      'total' => $lesson->getTotalItemCount(),
      'page' => $page,
      'pages' => $numberOfPages,
      'limitPerPage' => $lesson->getItemNumberPerPage(),
      'lesson' => $data,
    ];
  }
}