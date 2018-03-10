<?php
header('Content-Type: text/html; charset=utf-8');

$testSet = array(
    "haista", 
    "aakkostaa",
    "aallota",
    "aallottaa",
    "aaltoilla",
    "aateloida",
    "aavikoitua",
    "aavistaa",
    "aavistella",
    "abortoida",
    "absorboida",
    "absorboitua",
    "abstrahoida",
    "abstrahoitua",
    "abstraktistaa",
    "abstraktistua",
    "adaptoida",
    "adaptoitua",
    "adoptoida",
    "adsorboida",
    "adsorboitua",
    "aerobikata",
    "affisioida",
    "agitoida",
    "ahavoittaa",
    "ahavoitua",
    "ahdata",
    "ahdistaa",
    "ahdistella",
    "ahdistua",
    "ahertaa",
    "ahkeroida",
    "ahmaista",
    "ahmia",
    "ahnehtia",
    "ahtaa",
    "ahtauttaa",
    "ahtauttaa",
    "ahtautua",
    "ahtoutua",
    "aidata",
    "aidoittaa",
    "aientaa",
    "aiheellistaa",
    "aiheellistua",
    "aiheuttaa",
    "aiheutua",
    "aikailla",
    "aikaistaa",
    "aikaistua",
    "aikatauluttaa",
    "aikauttaa",
    "aikoa",
    "aikuistua",
    "ailahdella",
    "ailahtaa",
    "aineellistaa",
    "aineellistua",
    "aineistaa",
    "aineistua",
    "aisata",
    "aistia",
    "aitoa",
    "aivastaa",
    "aivastella",
    "aivastuttaa",
    "ajaa",
    "ajaantua",
    "ajankohtaistaa",
    "ajankohtaistua",
    "ajanmukaistaa",
    "ajanmukaistua",
    "ajantasaistaa",
    "ajastaa",
    "ajatella",
    "ajattaa",
    "ajatteluttaa",
    "ajautua",
    "ajelehtia",
    "ajella",
    "ajeluttaa",
    "ajettua",
    "ajoittaa",
    "ajoittua",
    "akkautua",
    "akklimatisoida",
    "akklimatisoitua",
    "akkreditoida",
    "akoittua",
    "akseptata",
    "akseptoida",
    "aktiivistaa",
    "aktiivistua",
    "aktivoida",
    "aktivoitua",
    "aktuaalistaa",
    "aktuaalistua",
    "aktualisoida",
    "aktualisoitua",
    "alentaa",
    "alentua",
    "aleta",
    "alistaa",
    "alistua",
    "alittaa",
    "alittua",
    "alkaa",
    "alkoholisoitua",
    "allastaa",
    "allergisoida",
    "allergisoitua",
    "allergistaa",
    "allergistua",
    "allokoida",
    "aloitella",
    "aloittaa",
    "altistaa",
    "altistua",
    "aluminoida",
    "alustaa",
    "amerikkalaistua",
    "ammatillistaa",
    "ammatillistua",
    "ammattilaistaa",
    "ammattilaistua",
    "ammentaa",
    "ammottaa",
    "ammua",
    "ammuskella",
    "ammuttaa",
    "ampaista",
    "ampua",
    "ampuilla",
  );
// example


include('verbicate.php');
// initialize and use cache
$conjugate = new Verbigate(false);

ksort($testSet);

print "<table>";
foreach ($testSet as $i => $word) {
  $me = $conjugate->PresentTenseMe($word);
  $you = $conjugate->PresentTenseYou($word);
  $she = $conjugate->PresentTenseShe($word);
  $we = $conjugate->PresentTenseWe($word);
  $youp = $conjugate->PresentTenseYouP($word);
  $they = $conjugate->PresentTenseThey($word);
  print "<tr>
    <td>".$word."</td>
    <td>minä ".$me["answer"]."</td>
    <td>sinä ".$you["answer"]."</td>
    <td>hän ".$she["answer"]."</td>
    <td>me ".$we["answer"]."</td>
    <td>te ".$youp["answer"]."</td>
    <td>he ".$they["answer"]."</td>
    <!--td>".$me["match"]."</td-->
  </tr>".PHP_EOL;
}
