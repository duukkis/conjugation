<?php

$c = file_get_contents("jouka.log");

function doMatch (string $word, string $haystack) {
    $word .= " ";
    do {
        $word = mb_substr($word, 0, -1);
        $pattern = '/;(' . $word . '[a-zäöå]*);(.*);(.*)\s/';
        preg_match_all($pattern, $haystack, $matches, PREG_PATTERN_ORDER);
        if (mb_strlen($word) <= 1) {
            return [];
        } else if (count($matches) > 0) {
            return $matches[3][0];
        }
    } while(true);
}


$res = doMatch("edäs", $c);
print_r($res);