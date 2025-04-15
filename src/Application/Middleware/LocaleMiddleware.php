<?php

namespace App\Application\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Translation\Translator;

class LocaleMiddleware
{
    private Translator $translator;

    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }

    public function __invoke(Request $request, Handler $handler): Response
    {
        $lang = $request->getQueryParams()['lang'] ?? null;

        if (!$lang) {
            $lang = $request->getHeaderLine('Accept-Language');
            $lang = substr($lang, 0, 2);
        }

        $lang = in_array($lang, ['en', 'it']) ? $lang : 'en';

        $this->translator->setLocale($lang);

        return $handler->handle($request);
    }
}
