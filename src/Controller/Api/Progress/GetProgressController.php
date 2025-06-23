<?php

namespace App\Controller\Api\Progress;

use App\Service\Progress\GetProgressByUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class GetProgressController extends AbstractController
{
    public function __construct(
        private GetProgressByUserService $getProgressByUserService,
    ) {}

    #[Route('/progress', name: 'api_progress_list', methods: ['GET'])]
    public function getUserProgress(Request $request): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'User not authenticated'], JsonResponse::HTTP_UNAUTHORIZED);
        }

        try {
            $progress = $this->getProgressByUserService->execute($user, $page);

            return $this->json($progress, context: ['groups' => 'progress']);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }
}