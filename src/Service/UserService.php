<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function getUserData(UserInterface $user): array
    {
        return [
            "id" => $user->getId(),
            "email" => $user->getEmail(),
            "roles" => $user->getRoles(),
        ];
    }

    public function getById(int $id): ?User
    {
        return $this->userRepository->find($id);
    }

    public function getAll(): array
    {
        return $this->userRepository->findAll();
    }

    public function createUser(array $data): array
    {
        $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);

        if ($existingUser) {
            throw new \Exception("Email already taken");
        }

        $user = new User();
        $user->setUsername($data['email']);
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);
        $this->userRepository->insert($user);

        return [
            "createdUserEmail" => $user->getEmail(),
        ];
    }

    public function editUser(int $id, array $data): void
    {
        $user = $this->userRepository->find($id);

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        if (isset($data['password'])) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['password']));
        }

        $this->userRepository->edit($user);
    }

    public function deleteUser(int $id): void
    {
        $user = $this->userRepository->find($id);
        if ($user) {
            $this->userRepository->delete($user);
        }
    }
}
