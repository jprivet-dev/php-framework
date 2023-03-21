<?php

namespace pages;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class PagesTest extends TestCase
{
    private Framework $framework;

    protected function setUp(): void
    {
        $routes = require __DIR__.'/../../src/app.php';

        $dispatcher = new EventDispatcher();
        $urlMatcher = new UrlMatcher($routes, new RequestContext());
        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $this->framework = new Framework($dispatcher, $urlMatcher, $controllerResolver, $argumentResolver);
    }

    public function testHello()
    {
        $request = Request::create('/hello');
        $response = $this->framework->handle($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Hello World', $response->getContent());
    }

//    public function testHelloFabien()
//    {
//        $request = Request::create('/hello/Fabien');
//        $response = $this->framework->handle($request);
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals('Hello Fabien', $response->getContent());
//    }
//
//    public function testBye()
//    {
//        $request = Request::create('/bye');
//        $response = $this->framework->handle($request);
//
//        $this->assertEquals(200, $response->getStatusCode());
//        $this->assertEquals('Goodbye!', $response->getContent());
//    }
}