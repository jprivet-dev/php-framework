<?php

namespace Controller;

use PHPUnit\Framework\TestCase;
use Simplex\Framework;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class LeapYearControllerTest extends TestCase
{

    public function test2000IsALeapYear()
    {
        $framework = $this->getFrameworkForResponse('2000');
        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Yep, 2000 is a leap year.', $response->getContent());
    }

    public function test2001IsNotALeapYear()
    {
        $framework = $this->getFrameworkForResponse('2001');
        $response = $framework->handle(new Request());

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Nope, 2001 is not a leap year.', $response->getContent());
    }

    private function getFrameworkForResponse(string $year): Framework
    {
        $context = $this->createMock(RequestContext::class);

        $matcher = $this->createMock(UrlMatcherInterface::class);
        $matcher
            ->expects($this->once())
            ->method('match')
            ->will(
                $this->returnValue([
                    '_route' => 'is_leap_year/{year}',
                    'year' => $year,
                    '_controller' => [new LeapYearController(), 'index'],
                ])
            );
        $matcher
            ->expects($this->once())
            ->method('getContext')
            ->will($this->returnValue($context));

        $controllerResolver = new ControllerResolver();
        $argumentResolver = new ArgumentResolver();

        return new Framework($matcher, $controllerResolver, $argumentResolver);
    }
}