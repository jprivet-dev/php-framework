<?php

namespace Controller;

use Model\Calendar;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LeapYearController
{
    public function index(Request $request, $year): Response
    {
        $content = (new Calendar)->isLeapYear($year)
            ? sprintf('Yep, %s is a leap year.', $year)
            : sprintf('Nope, %s is not a leap year.', $year);

        $response = new Response($content);
        $response->setTtl(10);

        return $response;
    }
}