<?php

use Controller\LeapYearController;
use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ArgumentResolverInterface;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
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

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }

    public function testLeapYearControllerResponse()
    {
        $context = $this->createMock(RequestContext::class);

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will(
                $this->returnValue([
                    '_route' => 'is_leap_year/{year}',
                    'year' => '2000',
                    '_controller' => [new LeapYearController(), 'index'],
                ])
            );
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($context));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        $framework = new Framework($matcher, $controllerResolver, $argumentResolver);
        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Yep, 2000 is a leap year.', $response->getContent());
    }
}