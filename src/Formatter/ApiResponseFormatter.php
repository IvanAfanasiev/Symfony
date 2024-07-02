<?php

namespace App\Formatter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ApiResponseFormatter
{
    private mixed $data;
    private int $statusCode = Response::HTTP_OK;
    private string $message = "OK";
    private array $errors = [];
    private array $additionalData = [];

    public function setErrors(array $errors): self
    {
        $this->errors = $errors;
        return $this;
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setAdditionalData(array $additionalData): self
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public static function success(mixed $data = null, string $message = "OK", array $additionalData = []): JsonResponse
    {
        $formatter = new self();
        return $formatter
            ->setData($data)
            ->setMessage($message)
            ->setAdditionalData($additionalData)
            ->format();
    }

    public static function error(string $message, array $errors = [], int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        $formatter = new self();
        return $formatter
            ->setData([])
            ->setMessage($message)
            ->setErrors($errors)
            ->setStatusCode($statusCode)
            ->format();
    }

    public function format(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->data,
            'message' => $this->message,
            'errors' => $this->errors,
            'code' => $this->statusCode,
            'additional' => $this->additionalData,
        ], $this->statusCode);
    }

}