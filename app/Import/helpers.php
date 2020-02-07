<?php

if (!function_exists('yesNoToBool')) {
    function yesNoToBool($val) {
        return strtolower($val) == 'yes';
    }
}

if (!function_exists('parseName')) {
    function parseName($fullNameString) {
        return explode(' ', $row['name']);
    }
    
}