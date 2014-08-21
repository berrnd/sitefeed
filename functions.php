<?php

function get_string_between($string, $start, $end) {
    $string = ' ' . $string;
    $i = strpos($string, $start);
    if ($i == 0)
        return '';
    $i += strlen($start);
    $len = strpos($string, $end, $i) - $i;
    return substr($string, $i, $len);
}

function base_url($relativeUrl = '') {
    $base_url = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
    $base_url .= '://' . $_SERVER['HTTP_HOST'];
    $base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

    return $base_url . $relativeUrl;
}
