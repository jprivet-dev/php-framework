<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
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
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

try {
    // Request attributes are extracted to keep our templates simple
    $attributes = $matcher->match($request->getPathInfo());
    $request->attributes->add($attributes);

    // the "_controller" request attribute contains the controller associated with the Request
    $controller = $controllerResolver->getController($request);
    $arguments = $argumentResolver->getArguments($request, $controller);

    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    // 500 errors are now managed correctly
    $response = new Response('An error occurred', 500);
}

$response->send();