<?php

if (!function_exists('yesNoToBool')) {
    function yesNoToBool($val) {
        return strtolower($val) == 'yes';
    }
}

if (!function_exists('parseName')) {
    function parseName($fullNameString) {
        return explode(' ', $fullNameString);
    }
}

if (!function_exists('rowToArray')) {
    function rowToArray($rowObj) {
        $arr = $rowObj->toArray();
        ksort($arr);

        return $arr;
    }
}

if (!function_exists('trimAndLower')) {
    function trimAndLower($string) {
        return trim(strtolower($string));
    }
}

if (!function_exists('arrayTrimStrings')) {
    function arrayTrimStrings (array $array) {
        return array_map(function ($value) {
            if (is_string($value)) {
                return trim($value);
            }
            if (is_array($value)) {
                return arrayTrimStrings($value);
            }
            return $value;
        }, $array);
    }
}

if (!function_exists('looksLikeEmailAddress')) {
    function looksLikeEmailAddress ($string) {
        return is_string($string) && $string != "" && strstr($string, '@');
    }
}