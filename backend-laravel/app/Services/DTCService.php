<?php

namespace App\Services;

use Illuminate\Support\Str;

class DTCService
{
    public function clean(string $code): string
    {
        return strtoupper(
            preg_replace('/[^A-Z0-9]/', '', $code)
        );
    }

    public function isValid(string $code): bool
    {
        return (bool) preg_match('/^[PCBU][0-9]{4,5}$/', $code);
    }

    public function cleanList(array $codes): array
    {
        $cleaned = [];

        foreach ($codes as $code) {
            if (!is_string($code)) {
                continue;
            }

            $c = $this->clean($code);

            if ($this->isValid($c)) {
                $cleaned[] = $c;
            }
        }

        sort($cleaned);

        return array_values(array_unique($cleaned));
    }

    public function classify(string $code): string
    {
        $secondDigit = $code[1] ?? '0';

        return $secondDigit === '0' ? 'generic' : 'manufacturer_specific';
    }
}
