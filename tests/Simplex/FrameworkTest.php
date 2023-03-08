<?php

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolverInterface;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class FrameworkTest extends TestCase
{
    public function testNotFoundHandling()
    {
        $framework = $this->getFrameworkForException(new ResourceNotFoundException());
        $response = $framework->handle(new Request());
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testErrorHandling()
    {
        $framework = $this->getFrameworkForException(new RuntimeException());
        $response = $framework->handle(new Request());
        $this->assertEquals(500, $response->getStatusCode());
    }

    private function getFrameworkForException(Exception $exception): Framework
    {
        $dispatcher = $this->createMock(EventDispatcher::class);
        $context = $this->createMock(RequestContext::class);

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will($this->throwException($exception));
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($context));

        $controllerResolver = $this->createMock(ControllerResolverInterface::class);
        $argumentResolver = $this->createMock(ArgumentResolverInterface::class);

        return new Framework($dispatcher, $matcher, $controllerResolver, $argumentResolver);
    }
}