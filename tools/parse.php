<?php

$c = file_get_contents("sanalista2024.csv");
$p = explode("\n", $c);

$data = [];
$hoof = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

foreach ($p as $line) {
    $pieces = explode(";", $line);
//    print $pieces[0] . "\n";
//    print $pieces[3] . "\n";
    if (count($pieces) == 4
    && strpos($pieces[2], "postpositio") === false
    && strpos($pieces[2], "prepositio") === false
    && strpos($pieces[2], "interjektio") === false
    && strpos($pieces[2], "adverbi") === false
    && strpos($pieces[2], "pronomini") === false
    ) {
        $key = substr($pieces[3], 0,1);
        $key2 = substr($pieces[3],1, 1);
        $key3 = substr($pieces[3],2, 1);
        if (in_array($key2, $hoof)) {
            $key .= $key2;
            if (in_array($key3, $hoof)) {
                $key .= $key3;
            }
        }
        $data[$key][] = utf8_strrev($pieces[0]);
    }
//    die();
}


foreach ($data as $key => $group) {
    sort($group);
    print $key . "\n";
    $fileName = "nominit/" . $key . ".txt";
    $toPrint = "";
    foreach ($group as $groupItem) {
        $toPrint .= $groupItem . "\n";
    }
    file_put_contents($fileName, $toPrint);
}


function utf8_strrev($str){
    return $str;
    preg_match_all('/./us', $str, $ar);
    return implode(array_reverse($ar[0]));
}