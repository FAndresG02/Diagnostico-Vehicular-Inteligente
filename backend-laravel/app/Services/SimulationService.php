<?php

namespace App\Services;

class SimulationService
{
    private const PREFIXES = ['P', 'C', 'B', 'U'];

    public function generateRandom(): string
    {
        $prefix = self::PREFIXES[array_rand(self::PREFIXES)];
        $numbers = random_int(1000, 99999);

        return $prefix . $numbers;
    }
}
