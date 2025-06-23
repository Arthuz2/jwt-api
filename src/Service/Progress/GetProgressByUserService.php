<?php

namespace App\Service\Progress;

use App\Entity\User;
use App\Repository\ProgressRepository;
use App\Utils;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;

class GetProgressByUserService
{
  public function __construct(
    private ProgressRepository $progressRepository,
    private PaginatorInterface $paginator,
  ) {}

  public function execute(User $user, int $page)
  {
    $progress = $this->progressRepository->findBy(['user' => $user]);
    if (!$progress) {
      return [];
    }

    $progress = $this->paginator->paginate($progress, $page, 10);

    $numberOfPages = ceil($progress->getTotalItemCount() / $progress->getItemNumberPerPage());
    if ($page > $numberOfPages) {
      throw new \OutOfBoundsException('Page number exceeds total pages available.', Response::HTTP_BAD_REQUEST);
    }


    $data = array_map(function ($progress) {;
      return [
        'id' => $progress->getId(),
        'user' => Utils::formatUser($progress->getUser()),
        'lesson' => Utils::formatLessons($progress->getLesson()),
        'completed_at' => Utils::formatDateTime($progress->getCompletedAt()),
      ];
    }, $progress->getItems());

    return [
      'total' => $progress->getTotalItemCount(),
      'page' => $page,
      'pages' => $numberOfPages,
      'limitPerPage' => $progress->getItemNumberPerPage(),
      'progress' => $data,
    ];
  }
}