<?php

namespace App\tests;

use PHPUnit\Framework\TestCase;
use App\Services\UserService;
use App\Repository\UserRepository;
use App\Formatter\ApiResponseFormatter;
use App\Entity\User;


class UserServiceTester extends TestCase{

//Test Find by id
    public function testGetById(){
        $result = $userService->getById(0);
        $expectedResult = $this->apiResponseFormatter->error("User not found");

        $this->assertEquals($expectedResult, $result);
    }

//Test Create
    public function testServiceCreate(){
        $userRepository = $this->createMock(UserRepository::class);
        $userRepository->method('findOneBy')->willReturn(null);
        $userRepository->method('insert')->willReturn(10);

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->method('hashPassword')->willReturn('hashed_password');

        $userService = new UserService($userRepository, $passwordHasher);

        $data = [
            'email' => 'testEmail',
            'password' => 'testPassword',
        ];

        $result = $userService->createUser($data);
        $expectedResult = [
            'createdUserEmail' => 'testEmail',
        ];

        $this->assertEquals($expectedResult, $result);
    }
}


?>