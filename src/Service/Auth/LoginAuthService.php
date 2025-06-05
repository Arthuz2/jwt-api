<?php

namespace App\Service\Auth;

use App\Controller\Exception\InvalidCredentialsException;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class LoginAuthService
{
  public function __construct(
    private UserRepository $userRepository,
    private UserPasswordHasherInterface $passwordHasher,
    private JWTTokenManagerInterface $jwtManager,
  ) {}

  public function execute(string $email, string $password): array
  {
    $user = $this->userRepository->findOneBy(['email' => $email]);

    if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
      throw new InvalidCredentialsException();
    }

    $token = $this->jwtManager->create($user);

    return [
      'token' => $token,
      'user' => [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'email' => $user->getEmail()
      ]
    ];
  }
}
