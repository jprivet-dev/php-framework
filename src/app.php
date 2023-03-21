<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

//
// Everything specific to our application in app.php
//

function render_template(Request $request): Response
{
    extract($request->attributes->all(), EXTR_SKIP);
    ob_start();
    require sprintf(__DIR__.'/../src/pages/%s.php', $_route); // Route names are used for template names

    return new Response(ob_get_clean());
}

$routes = new RouteCollection();

$routes->add(
    'hello',
    new Route('/hello/{name}', [
        'name' => 'World',
        '_controller' => 'render_template',
    ])
);

$routes->add(
    'bye',
    new Route('/bye', [
        '_controller' => 'render_template',
    ])
);

$routes->add(
    'leap_year',
    new Route('/is_leap_year/{year}', [
        'year' => date('Y'),
        '_controller' => 'Controller\LeapYearController::index',
    ])
);

return $routes;