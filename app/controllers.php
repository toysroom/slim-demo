<?php

declare(strict_types=1);

use App\Application\Controllers\CustomerController;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CustomerController::class => \DI\autowire(CustomerController::class),
    ]);
};