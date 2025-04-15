<?php

declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Controllers\CustomerController;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;
use Symfony\Component\Translation\Translator;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {

        $translator = $this->get(Translator::class);

        //$message = $translator->trans('greeting');
        // $message = $translator->trans('greeting', [], 'messages');
        // $message = $translator->trans('password_short', [], 'validation');
        $message = $translator->trans('esempio', [], 'messages');

        $response->getBody()->write($message);
        return $response;
    })->add($app->getContainer()->get(\App\Application\Middleware\ApiKeyMiddleware::class));

    
    $app->get('/test', function (Request $request, Response $response) {

        $translator = $this->get(Translator::class);
        
        $message = $translator->trans('users', ['%count%' => 20, '%xx%' => '!!!'], 'messages');

        $response->getBody()->write($message);
        return $response;
    });

    $app->group('/users', function (Group $group) {
        $group->get('', ListUsersAction::class);
        $group->get('/{id}', ViewUserAction::class);
    });
    //->add($app->getContainer()->get(\App\Application\Middleware\ApiKeyMiddleware::class));


    $app->group('/customers', function (Group $group) {
        $group->get('', [CustomerController::class, 'index']);
        $group->post('', [CustomerController::class, 'store']);
        $group->delete('/{id}', [CustomerController::class, 'destroy']);
    });
};
