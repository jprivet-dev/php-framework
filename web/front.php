<?php

use Simplex\ContentLengthListener;
use Simplex\Framework;
use Simplex\GoogleListener;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

//
// The generic code that powers our application in front.php
//

require_once __DIR__.'/../vendor/autoload.php';
$request = Request::createFromGlobals();
$routes = require __DIR__.'/../src/app.php';

$dispatcher = new EventDispatcher();
$dispatcher->addListener('response', [new ContentLengthListener(), 'onResponse'], -255);
$dispatcher->addListener('response', [new GoogleListener(), 'onResponse']);

$context = new RequestContext();
$matcher = new UrlMatcher($routes, $context);
$controllerResolver = new ControllerResolver();
$argumentResolver = new ArgumentResolver();

$framework = new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
$response = $framework->handle($request);

$response->send();