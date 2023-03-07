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
    ])
);

$routes->add('bye', new Route('/bye'));

return $routes;