<?php

namespace App\Helpers;

use App\Enums\HttpStatusCode;
use App\Enums\ResponseStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApiResponseHelper
{
    /**
     * Simple response method with auto-detected default messages
     *
     * @param ResponseStatus $status Response status enum
     * @param string|null $message Custom message (optional, will use default based on HTTP method)
     * @param mixed $data Response data (optional)
     * @param array|string|null $errors Error messages (optional, for error responses)
     * @param Request|null $request Request instance
     * @return JsonResponse
     */
    public static function response(
        ResponseStatus $status,
        ?string $message = null,
        $data = null,
        array|string|null $errors = null,
        ?Request $request = null
    ): JsonResponse {
        $request = $request ?? request();

        // Auto-detect default message based on HTTP method if not provided
        if ($message === null) {
            $message = self::getDefaultMessage($status, $request->method());
        }

        // Map ResponseStatus to HttpStatusCode and determine if it's valid
        [$httpStatusCode, $isValid] = self::mapStatusToHttpCode($status);

        $queryParams = $request->query();

        $response = [
            'details' => [
                'uri' => $request->fullUrl(),
                'method' => $request->method(),
                'status_code' => $httpStatusCode->value,
                'query' => !empty($queryParams) ? $queryParams : null,
            ],
            'data' => $data,
            'errors' => $errors ? (is_string($errors) ? [$errors] : $errors) : null,
            'message' => $message,
            'valid' => $isValid,
        ];

        return response()->json($response, $httpStatusCode->value);
    }

    /**
     * Get default message based on status and HTTP method
     */
    private static function getDefaultMessage(ResponseStatus $status, string $method): string
    {
        return match ($status) {
            ResponseStatus::SUCCESS => match (strtoupper($method)) {
                'GET' => 'Data retrieved successfully',
                'POST' => 'Data created successfully',
                'PUT', 'PATCH' => 'Data updated successfully',
                'DELETE' => 'Data deleted successfully',
                default => 'Operation completed successfully',
            },
            ResponseStatus::CREATED => 'Data created successfully',
            ResponseStatus::UPDATED => 'Data updated successfully',
            ResponseStatus::DELETED => 'Data deleted successfully',
            ResponseStatus::ERROR => 'An error occurred',
            ResponseStatus::VALIDATION_ERROR => 'Validation failed',
            ResponseStatus::NOT_FOUND => 'Resource not found',
            ResponseStatus::UNAUTHORIZED => 'Unauthorized',
            ResponseStatus::FORBIDDEN => 'Forbidden',
        };
    }

    /**
     * Map ResponseStatus to HttpStatusCode and validity
     */
    private static function mapStatusToHttpCode(ResponseStatus $status): array
    {
        return match ($status) {
            ResponseStatus::SUCCESS => [HttpStatusCode::OK, true],
            ResponseStatus::CREATED => [HttpStatusCode::CREATED, true],
            ResponseStatus::UPDATED => [HttpStatusCode::OK, true],
            ResponseStatus::DELETED => [HttpStatusCode::OK, true],
            ResponseStatus::ERROR => [HttpStatusCode::BAD_REQUEST, false],
            ResponseStatus::VALIDATION_ERROR => [HttpStatusCode::UNPROCESSABLE_ENTITY, false],
            ResponseStatus::NOT_FOUND => [HttpStatusCode::NOT_FOUND, false],
            ResponseStatus::UNAUTHORIZED => [HttpStatusCode::UNAUTHORIZED, false],
            ResponseStatus::FORBIDDEN => [HttpStatusCode::FORBIDDEN, false],
        };
    }
    /**
     * Create a standardized API response
     *
     * @param mixed $data The response data (array, collection, model, etc.)
     * @param string $message The success message
     * @param int|HttpStatusCode $statusCode HTTP status code
     * @param Request|null $request Request instance for details
     * @return JsonResponse
     */
    public static function success(
        $data = null,
        string $message = 'Success',
        int|HttpStatusCode $statusCode = HttpStatusCode::OK,
        ?Request $request = null
    ): JsonResponse {
        $request = $request ?? request();
        $code = $statusCode instanceof HttpStatusCode ? $statusCode->value : $statusCode;

        $queryParams = $request->query();

        $response = [
            'details' => [
                'uri' => $request->fullUrl(),
                'method' => $request->method(),
                'status_code' => $code,
                'query' => !empty($queryParams) ? $queryParams : null,
            ],
            'data' => $data,
            'errors' => null,
            'message' => $message,
            'valid' => true,
        ];

        return response()->json($response, $code);
    }

    /**
     * Create a standardized error API response
     *
     * @param array|string $errors Error messages or single error message
     * @param string $message The error message
     * @param int|HttpStatusCode $statusCode HTTP status code
     * @param string|null $rc Response code (optional)
     * @param Request|null $request Request instance for details
     * @return JsonResponse
     */
    public static function error(
        array|string $errors = [],
        string $message = 'An error occurred',
        int|HttpStatusCode $statusCode = HttpStatusCode::BAD_REQUEST,
        ?Request $request = null
    ): JsonResponse {
        $request = $request ?? request();
        $code = $statusCode instanceof HttpStatusCode ? $statusCode->value : $statusCode;

        // Convert string to array if needed
        if (is_string($errors)) {
            $errors = [$errors];
        }

        $queryParams = $request->query();

        $response = [
            'details' => [
                'uri' => $request->fullUrl(),
                'method' => $request->method(),
                'status_code' => $code,
                'query' => !empty($queryParams) ? $queryParams : null,
            ],
            'data' => null,
            'errors' => $errors ?: null,
            'message' => $message,
            'valid' => false,
        ];

        return response()->json($response, $code);
    }

    /**
     * Create a standardized validation error response
     *
     * @param array $errors Validation errors
     * @param string $message The error message
     * @param Request|null $request Request instance for details
     * @return JsonResponse
     */
    public static function validationError(
        array $errors,
        string $message = 'Validation failed',
        ?Request $request = null
    ): JsonResponse {
        return self::error($errors, $message, HttpStatusCode::UNPROCESSABLE_ENTITY, null, $request);
    }

    /**
     * Create a standardized not found response
     *
     * @param string $message The error message
     * @param Request|null $request Request instance for details
     * @return JsonResponse
     */
    public static function notFound(
        string $message = 'Resource not found',
        ?Request $request = null
    ): JsonResponse {
        return self::error([], $message, HttpStatusCode::NOT_FOUND, null, $request);
    }

    /**
     * Create a standardized unauthorized response
     *
     * @param string $message The error message
     * @param Request|null $request Request instance for details
     * @return JsonResponse
     */
    public static function unauthorized(
        string $message = 'Unauthorized',
        ?Request $request = null
    ): JsonResponse {
        return self::error([], $message, HttpStatusCode::UNAUTHORIZED, null, $request);
    }

    /**
     * Create a standardized forbidden response
     *
     * @param string $message The error message
     * @param Request|null $request Request instance for details
     * @return JsonResponse
     */
    public static function forbidden(
        string $message = 'Forbidden',
        ?Request $request = null
    ): JsonResponse {
        return self::error([], $message, HttpStatusCode::FORBIDDEN, null, $request);
    }
}
