<?php

declare(strict_types=1);

namespace App\Payment\Application\Converter\Money;

use function bcmul;

final class MoneyConverter
{
    private const PENNIES = "100";

    /**
     * @param numeric-string $amount
     * @return numeric-string
     */
    public static function convertToNumeric(string $amount): string
    {
        return bcmul($amount, self::PENNIES, 2);
    }

    public static function convertToStringMoney(int $amount): string
    {
        return 'e';
    }
}
