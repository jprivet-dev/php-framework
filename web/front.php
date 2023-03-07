<?php

use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

//
// The generic code that powers our application in front.php
//

require_once __DIR__.'/../vendor/autoload.php';

function render_template(Request $request): Response
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    require sprintf(__DIR__.'/../src/pages/%s.php', $_route); // Route names are used for template names

    return new Response(ob_get_clean());
}

$request = Request::createFromGlobals();
$routes = require __DIR__.'/../src/app.php';

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();