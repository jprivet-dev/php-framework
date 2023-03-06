<?php

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class PagesTest extends TestCase
{
    public function testHello()
    {

        ob_start();
        include __DIR__.'/../web/front.php';
        $content = ob_get_clean();

        $this->assertEquals('Hello Fabien', $content);
    }
}