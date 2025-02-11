<?php

$c = file_get_contents("joukahainen.xml");
$p = explode('</word>', $c);

$data = [];
$maximi = 0;

foreach ($p as $line) {
    if (strpos($line, '<word') !== false) {
        $line .= '</word>';
        $xml = simplexml_load_string($line);
//        if ($xml->classes->wclass != 'verb' && $xml->classes->wclass != 'abbreviation' && $xml->classes->wclass != 'adverb') {
        if (strpos($line, 'noun') !== false) {
            $w = (string) $xml->forms->form;
            if (strpos($w, '=') !== false) {
                $e = explode('=', $w);
                $w = $e[count($e) - 1];
            }
            $w = mb_strtolower($w, 'UTF-8');
            if (mb_strlen($w) > $maximi) {
                $maximi = mb_strlen($w);
            }
            $wrev = utf8_strrev($w);
//            print $xml->classes->wclass. ';';
            if (is_array($xml->inflection->infclass)) {
                $infclass = (string) $xml->inflection->infclass[0];
            } else {
                $infclass = (string) $xml->inflection->infclass;
            }
            $data[$wrev] = ["w" => $w, "infclass" => $infclass];
        }
    }
}
ksort($data);
foreach ($data as $rev => $act) {
    print ";".$rev . ";" . $act["w"] . ";" . $act["infclass"] . PHP_EOL;
}
// print_r($data);

/*
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
*/

function utf8_strrev($str){
    preg_match_all('/./us', $str, $ar);
    return implode(array_reverse($ar[0]));
}