<?php

namespace App\Service\Auth;

use App\Controller\Exception\UserAlreadyExistsException;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterAuthService
{
  public function __construct(
    private UserRepository $userRepository,
    private UserPasswordHasherInterface $passwordHasher,
  ) {}

  public function execute(string $name, string $email, string $password): array
  {

    $user = $this->userRepository->findOneBy(['email' => $email]);
    if ($user) {
      throw new UserAlreadyExistsException();
    }

    $user = new User();
    $passwordHashed = $this->passwordHasher->hashPassword($user, $password);
    $user->setName($name);
    $user->setEmail($email);
    $user->setPassword($passwordHashed);

    $this->userRepository->save($user);

    return [
      'user' => [
        'id' => $user->getId(),
        'name' => $user->getName(),
        'email' => $user->getEmail(),
      ]
    ];
  }
}