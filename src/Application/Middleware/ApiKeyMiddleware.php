<?php
namespace App\Application\Middleware;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Server\MiddlewareInterface;
use Slim\Psr7\Response as SlimResponse;

class ApiKeyMiddleware implements MiddlewareInterface
{
    private string $expectedApiKey;

    public function __construct(string $expectedApiKey)
    {
        $this->expectedApiKey = $expectedApiKey;
    }

    public function process(Request $request, RequestHandler $handler): Response
    {
        $apiKey = $request->getHeaderLine('X-Api-Key');

        if ($apiKey !== $this->expectedApiKey) {
            $response = new SlimResponse();
            $response->getBody()->write(json_encode([
                'error' => 'Unauthorized',
                'message' => 'Missing or invalid API key'
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        return $handler->handle($request);
    }
}
