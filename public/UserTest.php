<?php

// declare(strict_types=1);

// namespace App\tests;

// use App\Services\UserService;
// use App\Repository\UserRepository;
// use PHPUnit\Framework\TestCase;
// use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
// use App\Entity\User;

// class UserTest extends TestCase
// {
//     public function testAddUser()
//     {
//         // Create a mock of the UserRepository
//         $userRepository = $this->createMock(UserRepository::class);
//         $userRepository->method('findOneBy')->willReturn(null);
//         $userRepository->method('insert')->willReturn(10);

//         $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
//         $passwordHasher->method('hashPassword')->willReturn('hashed_password');

//         $userService = new UserService($userRepository, $passwordHasher);

//         $data = [
//             'email' => 'email_test',
//             'password' => 'passwordTest123',
//         ];

//         $result = $userService->createUser($data);
//         $expectedResult = [
//             'createdUserId' => null,
//         ];

//         $this->assertEquals($expectedResult, $result);
//     }
// }