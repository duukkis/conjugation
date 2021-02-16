<?php
include(__DIR__.'/testset.php');
include(__DIR__.'/../verbicate.php');
$conjugate = new Verb();

/**
 * Helper for creating set
 * Usage
 * 1. add verb to testset
 * 2. php create.php > data
 * 3. verify result
 * 4. copy paste into testset.php
 *
 * create the following looking testset
"aavistaa" => [
  "pre" => ["aavistan", "aavistat", "aavistaa", "aavistamme", "aavistatte", "aavistavat"],
  "imp" => ["aavistin", "aavistit", "aavisti", "aavistimme", "aavistitte", "aavistivat"],
  "perfekti" => ["aavistanut", "aavistaneet"],
  "imperatiivi" => ["aavista", "aavistakaa"],
],
 */
foreach($testSet AS $verb => $corr){
  print '"'.$verb.'" => [' . PHP_EOL;
  print "\t";
  print '"preesens" => [';
  print '"'.$conjugate->preesensMe($verb).'", ';
  print '"'.$conjugate->preesensYou($verb).'", ';
  print '"'.$conjugate->preesensSHe($verb).'", ';
  print '"'.$conjugate->preesensPluralWe($verb).'", ';
  print '"'.$conjugate->preesensPluralYou($verb).'", ';
  print '"'.$conjugate->preesensPluralThey($verb).'"],';
  print PHP_EOL;
  print "\t";
  print '"imperfect" => [';
  print '"'.$conjugate->imperfectMe($verb).'", ';
  print '"'.$conjugate->imperfectYou($verb).'", ';
  print '"'.$conjugate->imperfectSHe($verb).'", ';
  print '"'.$conjugate->imperfectPluralWe($verb).'", ';
  print '"'.$conjugate->imperfectPluralYou($verb).'", ';
  print '"'.$conjugate->imperfectPluralThey($verb).'"],';
  print PHP_EOL;
  print "\t";
  print '"perfect" => [';
  print '"'.$conjugate->perfectSingle($verb).'", ';
  print '"'.$conjugate->perfectPlural($verb).'"],';
  print PHP_EOL;
  print "\t";
  print '"imperative" => [';
  print '"'.$conjugate->imperativeSingle($verb).'", ';
  print '"'.$conjugate->imperativePlural($verb).'"],';
  print PHP_EOL;
  print "],";
  print PHP_EOL;
}