<?php

declare(strict_types=1);

use App\Application\Settings\SettingsInterface;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\Loader\JsonFileLoader;
use App\Application\Middleware\LocaleMiddleware;
use App\Application\Middleware\ResponseHeaderMiddleware;
use App\Application\Middleware\ApiKeyMiddleware;

return function (ContainerBuilder $containerBuilder) {
    $containerBuilder->addDefinitions([
        LoggerInterface::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $loggerSettings = $settings->get('logger');
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },
        PDO::class => function (ContainerInterface $c) {
            $settings = $c->get(SettingsInterface::class);

            $pdoSettings = $settings->get('db');
            $dsn = "mysql:host={$pdoSettings['host']};dbname={$pdoSettings['dbname']};port={$pdoSettings['port']};charset=utf8mb4";
    
            return new PDO($dsn, $pdoSettings['user'], $pdoSettings['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        },
        
        // Translator::class => function () {
        //     $translator = new Translator('en');
        //     $translator->addLoader('json', new JsonFileLoader());
        //     $translator->addResource('json', __DIR__ . '/../langs/en.json', 'en');
        //     $translator->addResource('json', __DIR__ . '/../langs/it.json', 'it');
        //     return $translator;
        // },

        Translator::class => function () {
            $translator = new Translator('en');
            $translator->addLoader('json', new JsonFileLoader());
            
            $translator->setFallbackLocales(['en']);

            $loadTranslationsFromDirectory = function ($lang) use ($translator) {
                $translationFiles = glob(__DIR__ . "/../langs/{$lang}/*.json");
                foreach ($translationFiles as $file) {
                    $domain = pathinfo($file, PATHINFO_FILENAME);
                    $translator->addResource('json', $file, $lang, $domain);
                }
            };

            $loadTranslationsFromDirectory('en');
            $loadTranslationsFromDirectory('it');

            return $translator;
        },

        LocaleMiddleware::class => function (ContainerInterface $c) {
            return new LocaleMiddleware($c->get(Translator::class));
        },

        ResponseHeaderMiddleware::class => function () {
            return new ResponseHeaderMiddleware();
        },

        ApiKeyMiddleware::class => function () {
            $apiKey = $_ENV['API_KEY'] ?? 'default-key';
            return new ApiKeyMiddleware($apiKey);
        },
    ]);
};
