<?php
header('Content-Type: text/html; charset=utf-8');

$testSet = array(
    "aakkostaa",       // 53
    "aallota",         // 75
    "aallottaa",       // 53
    "aaltoilla",       // 67
    "aateloida",       // 62
    "aavikoitua",      // 52
    "aavistaa",        // 53
    "aavistella",      // 67
    "abortoida",       // 62
    "absorboida",      // 62
    "absorboitua",     // 52
    "abstrahoida",     // 62
    "abstrahoitua",    // 52
    "abstraktistaa",   // 53
    "abstraktistua",   // 52
    "adaptoida",       // 62
    "adaptoitua",      // 52
    "adoptoida",       // 62
    "adsorboida",      // 62
    "adsorboitua",     // 52
    "aerobikata",      // 73
    "affisioida",      // 62
    "agitoida",        // 62
    "ahavoittaa",      // 53
    "ahavoitua",       // 52
    "ahdata",          // 73
    "ahdistaa",        // 53
    "ahdistella",      // 67
    "ahdistua",        // 52
    "ahertaa",         // 54
    "ahkeroida",       // 68
    "ahmaista",        // 66
    "ahmia",           // 61
    "ahnehtia",        // 61
    "ahtaa",           // 56
    "ahtauttaa",       // 53
    "ahtoutua",        // 52
    "aidata",          // 73
    "aidoittaa",       // 53
    "aientaa",         // 54
    "aiheellistaa",    // 53
    "aikailla",        // 67
    "ailahdella",      // 67
    "aisata",          // 73
    "aistia",          // 61
    "aivastella",      // 67
    "ajaa",            // 56
    "ajatella",        // 67
    "ajelehtia",       // 61
    "ajella",          // 67
    "akklimatisoida",  // 62
    "akseptata",       // 73
    "alentaa",         // 54
    "aleta",           // 72
    "alkaa",           // 56
    "aloitella",       // 67
    "ammentaa",        // 54
    "ammuskella",      // 67
    "ampaista",        // 66
    "ansaita",         // 69
    "antaa",           // 56
    "aplodeerata",     // 73
    "appaa",           // 56
    "aprikoida",       // 68
    "aprillata",       // 73
    "arvata",          // 73
    "asentaa",         // 54
    "askeltaa",        // 54
    "aterioida",       // 68
    "aueta",           // 74
    "aukaista",        // 66
    "aurata",          // 73
    "auttaa",          // 56
    "avartaa",         // 54
    "avata",           // 73
    "bingota",         // 75
    "diskota",         // 75
    "edeltää",         // 54
    "edetä",           // 72
    "ehkäistä",        // 66
    "ehtiä",           // 61
    "elehtiä",         // 61
    "empiä",           // 61
    "emännöidä",       // 68
    "enentää",         // 54
    "enetä",           // 72
    "englannintaa",    // 54
    "entää",           // 55
    "eritä",           // 75
    "erkanee",         // 72
    "erota",           // 74
    "etsiä",           // 61
    "fudia",           // 61
    "funtsia",         // 61
    "haaleta",         // 72
    "haastaa",         // 56
    "haista",          // 66
    "haistaa",         // 56
    "hajota",          // 74
    "hakea",           // 58
    "halaista",        // 66
    "haljeta",         // 74
    "halkaista",       // 66
    "hallita",         // 69
    "haluta",          // 75
    "halveta",         // 72
    "hamuta",          // 75
    "hangota",         // 74
    "hapata",          // 72
    "haravoida",       // 68
    "harkita",         // 69
    "harmeta",         // 72
    "harveta",         // 72
    "havaita",         // 69
    "havista",         // 66
    "heilimöidä",      // 68
    "hekumoida",       // 68
    "helistä",         // 66
    "hellitä",         // 75
    "henkäistä",       // 66
    "herjetä",         // 74
    "hervota",         // 74
    "hihhuloida",      // 68
    "hillitä",         // 69
    "hillota",         // 74
    "himoita",         // 69
    "himota",          // 74
    "hiota",           // 74
    "hirvitä",         // 75
    "hokea",           // 58
    "hulmuta",         // 75
    "huolita",         // 69
    "hyytää",          // 55
    "häiritä",         // 69
    "hälytä",          // 75
    "häätää",          // 55
    "ikävöidä",        // 68
    "ilakoida",        // 68
    "iloita",          // 69
    "imeä",            // 58
    "iskeä",           // 58
    "itkeä",           // 58
    "jakaa",           // 56
    "jaksaa",          // 56
    "juoda",           // 64
    "juosta",          // 70
    "jäädä",           // 63
    "kaartaa",         // 57
    "kaataa",          // 57
    "kahlita",         // 69
    "kaitsea",         // 58
    "kiitää",          // 55
    "kitkeä",          // 58
    "kokea",           // 58
    "koskea",          // 58
    "kulkea",          // 58
    "käydä",           // 65
    "liitää",          // 55
    "luoda",           // 64
    "lyödä",           // 64
    "lähteä",          // 60
    "myydä",           // 63
    "myödä",           // 64
    "nähdä",           // 71
    "piestä",          // 70
    "saada",           // 63
    "saartaa",         // 57
    "soutaa",          // 55
    "suoda",           // 64
    "syödä",           // 64
    "syöstä",          // 70
    "taitaa",          // 76
    "tehdä",           // 71
    "tietää",          // 76
    "tuntea",          // 59
    "tuoda",           // 64
    "viedä",           // 64
    "yltää",           // 55
  );
// example


include('verbicate.php');
// initialize and use cache
$conjugate = new Verbigate(false);

ksort($testSet);

print '<style>
td {padding : 3px;}
tr:nth-child(even) {background: #CCC}
tr:nth-child(odd) {background: #FFF}
</style>';
print '<table>';
foreach ($testSet as $i => $word) {
  $me = $conjugate->presentTenseMe($word);
  $you = $conjugate->presentTenseYou($word);
  $she = $conjugate->presentTenseShe($word);
  $we = $conjugate->presentTenseWe($word);
  $youp = $conjugate->presentTenseYouP($word);
  $they = $conjugate->presentTenseThey($word);
  print "<tr>
    <td>".$word."</td>
    <td>minä ".$me["answer"]."</td>
    <td>sinä ".$you["answer"]."</td>
    <td>hän ".$she["answer"]."</td>
    <td>me ".$we["answer"]."</td>
    <td>te ".$youp["answer"]."</td>
    <td>he ".$they["answer"]."</td>
    <td>".$me["match"]."</td>
  </tr>".PHP_EOL;
  $me = $conjugate->imperfectMe($word);
  $you = $conjugate->imperfectYou($word);
  $she = $conjugate->imperfectShe($word);
  $we = $conjugate->imperfectWe($word);
  $youp = $conjugate->imperfectYouP($word);
  $they = $conjugate->imperfectThey($word);
  print "<tr>
    <td><!--".$word."--></td>
    <td>minä ".$me["answer"]."</td>
    <td>sinä ".$you["answer"]."</td>
    <td>hän ".$she["answer"]."</td>
    <td>me ".$we["answer"]."</td>
    <td>te ".$youp["answer"]."</td>
    <td>he ".$they["answer"]."</td>
    <td>".$me["match"]."</td>
  </tr>".PHP_EOL;
}
