<?php
declare(strict_types=1);

namespace App;

use Monolog\Logger;
use Slim\Http\StatusCode;

class Generic
{
    protected $container;

    protected $responseSet;

    protected $response;

    protected $logger;

    public function __construct($container)
    {
        $this->container = $container;

        $response = $container->get('response');
        $this->response = $response;

        $this->responseSet = [
            0 => ['ok', StatusCode::HTTP_OK],
            1 => ['problem occured in executing method', StatusCode::HTTP_BAD_REQUEST],
            200 => ['ok', StatusCode::HTTP_OK],
            400 => ['invalid request', StatusCode::HTTP_BAD_REQUEST],
            404 => ['not found', StatusCode::HTTP_NOT_FOUND],
            500 => ['exception thrown', StatusCode::HTTP_INTERNAL_SERVER_ERROR],
        ];
        $this->logger = $container->get('logger');
    }

    public function healthCheck()
    {
        return 'server is up and running.';
    }

    protected function responder(
        int $code = StatusCode::HTTP_INTERNAL_SERVER_ERROR,
            $result = null
    )   {
        if (!isset($this->responseSet[$code])) {
            $message = 'undefined response in response_set';
            $result = $code;
            $code = StatusCode::HTTP_INTERNAL_SERVER_ERROR;
            $statusCode = StatusCode::HTTP_INTERNAL_SERVER_ERROR;
        } else {
            [$message, $statusCode] = $this->responseSet[$code];
        }

        $responseBody = [
            'code' => $code,
            'message' => $message,
            'result' => $result,
            'execution_time' => \round(\microtime(true) - $_SERVER['REQUEST_TIME_FLOAT'], 3),
            'memory_peak' => \round((\memory_get_peak_usage(true) / 1024) / 1024, 3),
        ];

        if ($statusCode >= StatusCode::HTTP_BAD_REQUEST) {
            $this->logger->error($message, $responseBody);
        }

        return $this->response->withJSON($responseBody, $statusCode, \JSON_PRETTY_PRINT);
    }
}