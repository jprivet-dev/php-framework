<?php

namespace Model;

class Calendar
{
    function isLeapYear(int $year = null): bool
    {
        if (null === $year) {
            $year = date('Y');
        }
        
        return 0 === $year % 400 || (0 === $year % 4 && 0 !== $year % 100);
    }
}