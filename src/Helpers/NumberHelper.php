<?php
namespace TimeShow\Repository\Helpers;

class NumberHelper
{
    /**
     * Check if a number is even
     * @param $number
     * @return bool
     *
     */
    public static function isEven($number): bool
    {
        return $number % 2 === 0;
    }

    /**
     * Check if a number is odd
     * @param $number
     * @return bool
     *
     */
    public static function isOdd($number): bool
    {
        return !$number % 2 === 0;
    }

    /**
     * Format numbers to two decimal places
     * @param float $number
     * @param int $decimals
     * @return string
     *
     * @example 1234.5678 => 1,234.57
     */
    public static function format(float $number, int $decimals = 2) : string
    {
        return number_format($number, $decimals, '.', '');
    }

    /**
     * Calculate percentage
     * @param float $number
     * @param float $total
     * @return string
     *
     * @example (5, 20) => 25%
     */
    public static function calculatePercentage(float $number, float $total) : string
    {
        if ($total <= 0 || $number < 0){
            return '0%';
        }

        $percentage = ($number / $total) * 100;
        return self::format($percentage) . '%';
    }

    /**
     * Generate a random number within a specified range
     * @param int $min
     * @param int $max
     * @return int
     *
     * @example (1, 100) => 45
     */
    public static function generateOneRandomNumber(int $min, int $max) : int
    {
        if ($min < 0 || $max < 0 || $min > $max){
            return 0;
        }
        return rand($min, $max);
    }

}