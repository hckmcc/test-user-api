<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;

/**
 * Глобальный обработчик исключений приложения.
 * Отвечает за преобразование различных исключений в соответствующие HTTP-ответы.
 */
class Handler extends ExceptionHandler
{
    /**
     * Регистрация callback-функций для обработки исключений.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Преобразование исключения в HTTP-ответ.
     *
     * @param \Illuminate\Http\Request $request
     * @param Throwable $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        // Для API-маршрутов используем специальную обработку исключений
        if ($request->is('api/*')) {
            return $this->handleApiException($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * Обработка исключений для API-запросов.
     * Преобразует различные типы исключений в структурированный JSON-ответ.
     *
     * @param Throwable $exception
     * @return \Illuminate\Http\JsonResponse
     */
    private function handleApiException(Throwable $exception)
    {
        $statusCode = 500;
        $response = [
            'message' => 'Internal server error',
            'type' => 'error'
        ];

        // 404: Ресурс не найден
        if ($exception instanceof ModelNotFoundException) {
            $statusCode = 404;
            $response['message'] = 'Resource not found';
            $response['type'] = 'not_found';
        }

        // 422: Ошибка валидации данных
        if ($exception instanceof ValidationException) {
            $statusCode = 422;
            $response['message'] = 'The given data was invalid';
            $response['type'] = 'validation_error';
            $response['errors'] = $exception->errors();
        }

        // Обработка ошибок базы данных
        if ($exception instanceof QueryException) {
            // 409: Конфликт при дублировании уникальных значений
            if ($exception->getCode() === '23000') {
                $statusCode = 409;
                $response['message'] = 'Duplicate entry';
                $response['type'] = 'duplicate_error';
            }
        }

        // 404: Маршрут не найден
        if ($exception instanceof NotFoundHttpException) {
            $statusCode = 404;
            $response['message'] = 'Route not found';
            $response['type'] = 'route_not_found';
        }

        // 405: Метод не разрешен
        if ($exception instanceof MethodNotAllowedHttpException) {
            $statusCode = 405;
            $response['message'] = 'Method not allowed';
            $response['type'] = 'method_not_allowed';
        }

        // В режиме отладки добавляем дополнительную информацию об ошибке
        if (config('app.debug')) {
            $response['debug'] = [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTrace()
            ];
        }

        return response()->json($response, $statusCode);
    }
}
