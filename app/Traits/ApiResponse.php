<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

trait ApiResponse
{
    /**
     * Success response
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'status_code' => $statusCode
        ], $statusCode);
    }

    /**
     * Error response
     *
     * @param string $message
     * @param int $statusCode
     * @param mixed $errors
     * @return JsonResponse
     */
    protected function errorResponse(string $message = 'Error occurred', int $statusCode = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $statusCode
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    /**
     * Validation error response with only first error message
     *
     * @param mixed $errors
     * @param string $message
     * @return JsonResponse
     */
    protected function validationErrorResponse($errors, string $message = 'Validation failed'): JsonResponse
    {
        return $this->errorResponse($message, 422, $errors);
    }

    /**
     * Custom validation method that returns only the first error message
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return array
     * @throws ValidationException
     */
    protected function validateRequest(array $data, array $rules, array $messages = [], array $attributes = []): array
    {
        try {
            return validator($data, $rules, $messages, $attributes)->validate();
        } catch (ValidationException $e) {
            // Get the first error message from the first field
            $firstError = '';
            $errors = $e->errors();

            if (!empty($errors)) {
                $firstField = array_key_first($errors);
                $firstError = $errors[$firstField][0] ?? 'Validation failed';
            }

            throw new ValidationException(
                validator($data, $rules, $messages, $attributes),
                $this->errorResponse($firstError, 422)
            );
        }
    }

    /**
     * Simple validation method that returns validation result without throwing exception
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return array|false Returns validated data on success, false on failure
     */
    protected function validateData(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        $validator = validator($data, $rules, $messages, $attributes);

        if ($validator->fails()) {
            // Get the first error message from the first field
            $firstError = '';
            $errors = $validator->errors();

            if (!empty($errors)) {
                $firstField = array_key_first($errors->toArray());
                $firstError = $errors->first($firstField) ?? 'Validation failed';
            }

            return false;
        }

        return $validator->validated();
    }

    /**
     * Validate request and return custom error response if validation fails
     *
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $attributes
     * @return array|JsonResponse Returns validated data on success, JsonResponse on failure
     */
    protected function validateWithCustomError(array $data, array $rules, array $messages = [], array $attributes = [])
    {
        $validator = validator($data, $rules, $messages, $attributes);

        if ($validator->fails()) {
            // Get the first error message from the first field
            $firstError = '';
            $errors = $validator->errors();

            if (!empty($errors)) {
                $firstField = array_key_first($errors->toArray());
                $firstError = $errors->first($firstField) ?? 'Validation failed';
            }

            return $this->errorResponse($firstError, 422);
        }

        return $validator->validated();
    }

    /**
     * Not found response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function notFoundResponse(string $message = 'Resource not found'): JsonResponse
    {
        return $this->errorResponse($message, 404);
    }

    /**
     * Unauthorized response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function unauthorizedResponse(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->errorResponse($message, 401);
    }

    /**
     * Forbidden response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function forbiddenResponse(string $message = 'Forbidden'): JsonResponse
    {
        return $this->errorResponse($message, 403);
    }

    /**
     * Server error response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function serverErrorResponse(string $message = 'Internal server error'): JsonResponse
    {
        return $this->errorResponse($message, 500);
    }

    /**
     * Created response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    protected function createdResponse($data = null, string $message = 'Resource created successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 201);
    }

    /**
     * Updated response
     *
     * @param mixed $data
     * @param string $message
     * @return JsonResponse
     */
    protected function updatedResponse($data = null, string $message = 'Resource updated successfully'): JsonResponse
    {
        return $this->successResponse($data, $message, 200);
    }

    /**
     * Deleted response
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function deletedResponse(string $message = 'Resource deleted successfully'): JsonResponse
    {
        return $this->successResponse(null, $message, 200);
    }
}
