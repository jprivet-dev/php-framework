<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

//
// Everything specific to our application in app.php
//

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

return $routes;