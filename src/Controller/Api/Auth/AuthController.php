<?php

namespace App\Controller\Api\Auth;

use App\Controller\DTO\Auth\LoginRequest;
use App\Controller\DTO\Auth\RegisterRequest;
use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AuthController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
        private JWTTokenManagerInterface $jwtManager,
        private UserPasswordHasherInterface $passwordHasher
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

        $user = $this->userRepository->findOneBy(['email' => $loginRequest->getEmail()]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $loginRequest->getPassword())) {
            return $this->json(['error' => 'invalid credentials'], status: Response::HTTP_UNAUTHORIZED);
        }

        $token = $this->jwtManager->create($user);

        return $this->json([
            'token' => $token,
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ]
        ]);
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

        $user = $this->userRepository->findOneBy(['email' => $registerRequest->getEmail()]);
        if ($user) {
            return $this->json(['error' => 'alread exists user with this email'], Response::HTTP_CONFLICT);
        }

        $user = new User(
            name: $registerRequest->getName(),
            email: $registerRequest->getEmail(),
        );
        $passwordHashed = $this->passwordHasher->hashPassword($user, $registerRequest->getPassword());
        $user->setPassword($passwordHashed);

        $this->userRepository->save($user);

        return $this->json([
            'user' => [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail(),
            ]
        ], Response::HTTP_CREATED);
    }

    #[Route('auth/me', name: 'api_me', methods: ['GET'])]
    public function me(User $user): JsonResponse
    {
        return $this->json([
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }
}
