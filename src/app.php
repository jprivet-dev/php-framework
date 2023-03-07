<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

//
// Everything specific to our application in app.php
//

function is_leap_year(int $year): bool
{
    return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
}

class LeapYearController
{
    public function index(Request $request): Response
    {
        $year = $request->attributes->get('year');
        $content = is_leap_year($year)
            ? sprintf('Yep, %s is a leap year.', $year)
            : sprintf('Nope, %s is not a leap year.', $year);

        return new Response($content);
    }
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
        '_controller' => [new LeapYearController, 'index'],
    ])
);

return $routes;