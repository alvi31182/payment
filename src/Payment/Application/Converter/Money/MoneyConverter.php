<?php

declare(strict_types=1);

namespace App\Payment\Application\Converter\Money;

class MoneyConverter
{
    private const PENNIES = "100";

    /**
     * @param numeric-string $amount
     */
    public static function convertToBaseUnit(string $amount): int
    {
        return (int) bcmul($amount, self::PENNIES, 2);
    }

    public static function convertToCurrency(int $amount): string
    {
        $format = number_format($amount, 2, '.', ' ');
        dd($format);
    }
}
