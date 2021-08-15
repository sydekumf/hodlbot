<?php

namespace App\Service;

class LocaleService
{
    /**
     * Holds dollar to euro ratio
     */
    private const DOLLAR_TO_EUR_RATIO = 0.83;

    /**
     * @param float $number
     * @return string
     */
    public static function reformat(float $number)
    {
        return number_format($number, 2, ',', '.');
    }

    /**
     * @param float $number
     * @param float $ratio
     * @return float|int
     */
    public static function currency(float $number, float $ratio = self::DOLLAR_TO_EUR_RATIO)
    {
        return $number *  self::DOLLAR_TO_EUR_RATIO;
    }
}
