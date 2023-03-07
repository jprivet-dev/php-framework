<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$response = new Response();

$routes = new RouteCollection();
$routes->add(
    'hello',
    new Route('/hello/{name}', [
        'name' => 'World',
    ])
);
$routes->add('bye', new Route('/bye'));

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try {
    // Request attributes are extracted to keep our templates simple
    extract($matcher->match($request->getPathInfo()), EXTR_SKIP);
    ob_start();
    require sprintf(__DIR__.'/../src/pages/%s.php', $_route); // Route names are used for template names
    $response = new Response(ob_get_clean());
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    // 500 errors are now managed correctly
    $response = new Response('An error occurred', 500);
}

$response->send();