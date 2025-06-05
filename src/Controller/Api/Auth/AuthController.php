<?php

namespace App\Controller\Api\Auth;

use App\Controller\DTO\Auth\LoginRequest;
use App\Controller\DTO\Auth\RegisterRequest;
use App\Entity\User;
use App\Service\Auth\LoginAuthService;
use App\Service\Auth\RegisterAuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AuthController extends AbstractController
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private LoginAuthService $loginAuthService,
        private RegisterAuthService $registerAuthService,
    ) {}

    #[Route('/auth/login', name: 'api_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $loginRequest = $this->serializer->deserialize(
            $request->getContent(),
            LoginRequest::class,
            'json'
        );

        $errors = $this->validator->validate($loginRequest);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->loginAuthService->execute(
                email: $loginRequest->getEmail(),
                password: $loginRequest->getPassword(),
            );

            return $this->json($result);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode());
        }
    }

    #[Route('/auth/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request)
    {
        $registerRequest = $this->serializer->deserialize(
            $request->getContent(),
            RegisterRequest::class,
            'json'
        );

        $errors = $this->validator->validate($registerRequest);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        try {
            $result = $this->registerAuthService->execute(
                name: $registerRequest->getName(),
                email: $registerRequest->getEmail(),
                password: $registerRequest->getPassword(),
            );

            return $this->json($result, status: Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], status: $e->getCode());
        }
    }

    #[Route('auth/me', name: 'api_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->json(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
