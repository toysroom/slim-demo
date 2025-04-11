<?php

declare(strict_types=1);

use App\Application\Services\CustomerService;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        CustomerService::class => \DI\autowire(CustomerService::class),
    ]);
};
