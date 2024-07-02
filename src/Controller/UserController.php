<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Formatter\ApiResponseFormatter;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('api/users/show', name:'userShow', methods: ['GET'])]   
    public function usersShow(int $val = 1): JsonResponse{
        $user = $this->getUser();
        dd($user);

        return new JsonResponse([
            'data'=>[
                'id'=>$user->getId(),
                'email'=>$user->getEmail()
            ],
            'messages'=>NULL,
            'errors'=>NULL,
            'statusCode'=>200,
            'additionalData'=>NULL,
        ]);
    }

    #[Route('/api/user/me', name: 'getMe', methods: ["GET"])]
    #[IsGranted("ROLE_USER")]
    public function getMe(): JsonResponse
    {
        $user = $this->getUser();
        return $this->apiResponseFormatter->success($this->userService->getUserData($user));
    }

    #[Route('/api/user/all', name: 'getAll', methods: ["GET"])]
    #[IsGranted("ROLE_SHOW_USERS")]
    public function getAll(): JsonResponse
    {
        $users = $this->userService->getAll();
        $data = array_map([$this->userService, 'getUserData'], $users);

        return $this->apiResponseFormatter->success($data);
    }

    #[Route('/api/user/{id}', name: 'getOne', methods: ["GET"])]
    #[IsGranted("ROLE_SHOW_USER")]
    public function getOne(int $id): JsonResponse
    {
        $user = $this->userService->getOne($id);
        if ($user) {
            $data = $this->userService->getUserData($user);
            return $this->apiResponseFormatter->success($data);
        } else {
            return $this->apiResponseFormatter->error("User not found");
        }
    }
}


?>