<?php

namespace common\models\report;



class NumberFormatterUA
{
    /**
     * Метод для преобразования числа в строку (100.00 -> сто гривень 00 копійок)
     *
     * @param float $number
     * @return string
     */
    public static function numberToString($number)
    {
        $integerPart = floor($number);
        $decimalPart = round(($number - $integerPart) * 100);

        $integerWords = self::numToWords($integerPart);
        $decimalWords = str_pad($decimalPart, 2, '0', STR_PAD_LEFT);

        return "$integerWords гривень $decimalWords копійок";
    }

    /**
     * Вспомогательная функция для преобразования числа в слова
     * @param int $num
     * @return string
     */
    private static function numToWords($num)
    {
        $units = ['', 'одна', 'дві', 'три', 'чотири', 'п’ять', 'шість', 'сім', 'вісім', 'дев’ять'];
        $teens = ['десять', 'одинадцять', 'дванадцять', 'тринадцять', 'чотирнадцять', 'п’ятнадцять', 'шістнадцять', 'сімнадцять', 'вісімнадцять', 'дев’ятнадцять'];
        $tens = ['', '', 'двадцять', 'тридцять', 'сорок', 'п’ятдесят', 'шістдесят', 'сімдесят', 'вісімдесят', 'дев’яносто'];
        $hundreds = ['', 'сто', 'двісті', 'триста', 'чотириста', 'п’ятсот', 'шістсот', 'сімсот', 'вісімсот', 'дев’ятсот'];
        $thousands = ['тисяча', 'тисячі', 'тисяч'];
        $millions = ['мільйон', 'мільйони', 'мільйонів'];

        if ($num == 0) {
            return 'нуль';
        }

        $result = '';

        if ($num >= 1000000) {
            $millionsPart = floor($num / 1000000);
            $result .= self::numToWords($millionsPart) . ' ' . self::getWordForm($millionsPart, $millions) . ' ';
            $num %= 1000000;
        }

        if ($num >= 1000) {
            $thousandsPart = floor($num / 1000);
            $result .= self::numToWords($thousandsPart) . ' ' . self::getWordForm($thousandsPart, $thousands) . ' ';
            $num %= 1000;
        }

        if ($num >= 100) {
            $result .= $hundreds[floor($num / 100)] . ' ';
            $num %= 100;
        }

        if ($num >= 10 && $num <= 19) {
            $result .= $teens[$num - 10];
        } else {
            if ($num >= 20) {
                $result .= $tens[floor($num / 10)] . ' ';
                $num %= 10;
            }

            if ($num > 0) {
                $result .= $units[$num];
            }
        }

        return trim($result);
    }

    /**
     * Функция для определения правильной формы слова (тысяча, тысячи, тысяч)
     * @param int $num
     * @param array $forms
     * @return string
     */
    private static function getWordForm($num, $forms)
    {
        $num = abs($num) % 100;
        if ($num > 10 && $num < 20) {
            return $forms[2];
        }
        $num = $num % 10;
        if ($num == 1) {
            return $forms[0];
        }
        if ($num >= 2 && $num <= 4) {
            return $forms[1];
        }
        return $forms[2];
    }
}
