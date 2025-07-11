<?php

namespace App\Services;

use Random\RandomException;

class RandomNumberGenerator
{
    /**
     * @throws RandomException
     */
    public function generate(int $min = 1, int $max = 1000): int
    {
        return random_int($min, $max);
    }
}