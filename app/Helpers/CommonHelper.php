<?php

namespace App\Helpers;

class CommonHelper
{
    public static function toRomanNumeral($number): string
    {
        $map = [
            'XL' => 40,
            'X' => 10,
            'IX' => 9,
            'V' => 5,
            'IV' => 4,
            'I' => 1
        ];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $value) {
                if ($number >= $value) {
                    $number -= $value;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
}
