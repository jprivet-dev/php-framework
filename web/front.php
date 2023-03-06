<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__.'/../vendor/autoload.php';

$request = Request::createFromGlobals();
$response = new Response();

$map = [
    '/hello' => 'hello.php',
    '/bye' => 'bye.php',
];

$path = $request->getPathInfo();
if (isset($map[$path])) {
    require __DIR__.'/../src/pages/'.$map[$path];
} else {
    $response->setStatusCode(404);
    $response->setContent('Not Found');
}

$response->send();