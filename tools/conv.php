<?php

$grads = [
    ['sw', 'tt', 'av1'],
    ['sw', 'pp', 'av1'],
    ['sw', 'kk', 'av1'],
    ['sw', 'mp', 'av1'],
    ['sw', 'p', 'av1'],
    ['sw', 'nt', 'av1'],
    ['sw', 'lt', 'av1'],
    ['sw', 'rt', 'av1'],
    ['sw', 't', 'av1'],
    ['sw', 'nk', 'av1'],
    ['sw', 'uku', 'av1'],
    ['sw', 'yky', 'av1'],
    ['ws', 'b', 'av2'],
    ['ws', 'g', 'av2'],
    ['ws', 't', 'av2'],
    ['ws', 'p', 'av2'],
    ['ws', 'k', 'av2'],
    ['ws', 'mm', 'av2'],
    ['ws', 'v', 'av2'],
    ['ws', 'nn', 'av2'],
    ['ws', 'll', 'av2'],
    ['ws', 'rr', 'av2'],
    ['ws', 'd', 'av2'],
    ['ws', 'ng', 'av2'],
    ['sw', 'k>j', 'av3'],
    ['ws', 'j>k', 'av4'],
    ['sw', 'k>', 'av5'],
    ['ws', '>k', 'av6']
];

define('SUBST', 1);
define('ADJ', 2);

define('VOWEL_DEFAULT', 0);
define('VOWEL_FRONT', 1);
define('VOWEL_BACK', 2);
define('VOWEL_BOTH', 3);

# Gradation types
define('GRAD_NONE', 0);
define('GRAD_SW', 1);
define('GRAD_WS', 2);
define('GRAD_WEAK', 3);
define('GRAD_STRONG', 4);


$modern_classmap = [
    ['valo', 'sw', [
        [null, '(.*)', 'valo'],
        ['k>', '(ko)ko', 'koko'],
        ['k>', '(.*uo)ko', 'ruoko'],
        ['kk', '(.*k)kU', 'alku'],
        ['uku', '(.*U)kU', 'luku'],
        ['k>', '(..U)kU', 'tiuku'],
        ['k>', '(.*)kU', 'alku'],
        ['lt', '(.*l)tO', 'aalto'],
        ['nt', '(.*n)tO', 'anto'],
        ['nt', '(.*n)tU', 'lintu'],
        ['nk', '(.*n)kO', 'hanko'],
        ['tt', '(.*t)tU', 'hattu'],
        ['tt', '(.*t)tO', 'liitto'],
        ['nk', '(.*n)kU', 'hinku'],
        ['pp', '(.*p)pU', 'hoppu'],
        ['rt', '(.*r)tO', 'kaarto'],
        ['pp', '(.*p)pO', 'kippo'],
        ['mp', '(.*m)pO', 'sampo'],
        ['mp', '(.*m)pU', 'kumpu'],
        ['t', '(.*)tU', 'laatu'],
        ['p', '(.*)pU', 'apu'],
        ['p', '(.*)pO', 'lepo'],
        ['t', '(.*)tO', 'leuto'],
        ['kk', '(.*k)kO', 'verkko'],
        ['k>', '(.*h)kO', 'vihko'],
        ['k>', '(.*)kO', 'verkko']
    ]],
    ['arvelu', 'sw', [
        [null, '(.*Ce[lr])O', 'hontelo', [2]],
        [null, '(.*)', 'arvelu'],
        ['nk', '(.*n)kO', 'alanko'],
        ['nt', '(.*n)tO', 'avanto'],
        ['kk', '(.*k)kO', 'laatikko'],
        ['tt', '(.*t)tO', 'pihatto'],
        ['tt', '(.*t)tU', 'raamattu']
    ]],
    ['autio', '-', [
        [null, '(.*)', 'autio']
    ]],
    ['kiiski', '-', [
        [null, '(.*)i', 'kiiski']
    ]],
    ['siisti', '-', [
        [null, '(.*)i', 'siisti']
    ]],
    ['risti', 'sw', [
        [null, '(.*)i', 'risti'],
        ['pp', '(pop)pi', 'pop'],
        ['pp', '(.*p)pi', 'keppi'],
        ['lt', '(.*l)ti', 'pelti'],
        ['nk', '(.*n)ki', 'renki'],
        ['kk', '(punk)ki', 'punk'],
        ['kk', '(.*k)ki', 'takki'],
        ['tt', '(.*t)ti', 'tatti'],
        ['nt', '(.*n)ti', 'tunti'],
        ['p', '(.*)pi', 'hupi'],
        ['t', '(.*)ti', 'vati'],
        ['k>', '(.*)ki', 'takki']
    ]],
    ['paperi', 'sw', [
        [null, '(.*)i', 'paperi'],
        ['nt', '(.*n)ti', 'hollanti'],
        ['nk', '(.*n)ki', 'killinki'],
        ['kk', '(.*k)ki', 'kajakki'],
        ['tt', '(.*t)ti', 'salaatti'],
        ['pp', '(.*p)pi', 'sinappi'],
        ['t', '(.*)ti', 'konvehti']
    ]],
    ['edam', '-', [
        [null, '(.*C)', 'edam']
    ]],
    ['kalsium', '-', [
        [null, '(.*)i', 'fan'],
        [null, '(.*)', 'kalsium']
    ]],
    ['lovi', 'sw', [
        [null, '(.*)i', 'lovi'],
        ['nk', '(.*n)ki', 'hanki'],
        ['pp', '(.*p)pi', 'happi'],
        ['mp', '(.*lam)pi', 'lampi'],
        ['mp', '(.*m)pi', 'sampi'],
        ['kk', '(.*k)ki', 'kaikki'],
        ['k>j', '(.*)ki', 'kylki'],
        ['t', '(.*lah)ti', 'lahti'],
        ['t', '(.*h)ti', 'lehti'],
        ['p', '(.*)pi', 'siipi'],
        ['k>', '(.*i)ki', 'piki'],
        ['k>', '(.*)ki', 'kaikki']
    ]],
    ['toholampi', '-', [
        [null, '(.*lam)pi', 'toholampi']
    ]],
    ['suksi', '-', [
        [null, '(.*u)ksi', 'suksi']
    ]],
    ['veli', '-', [
        [null, '(.*el)i', 'veli']
    ]],
    ['nalle', 'sw', [
        [null, '(.*Ce)', 'nalle'],
        [null, '(.*Cé)', 'nalle'],
        [null, '(.*[iu]e)', 'nalle'],
        ['tt', '(.*t)te', 'atte'],
        ['pp', '(.*p)pe', 'hjerppe'],
        ['kk', '(.*k)ke', 'nukke']
    ]],
    ['kala', 'sw', [
        [null, '(.*)A', 'kala'],
        ['tt', '(.*t)tA', 'aitta'],
        ['nk', '(.*n)kA', 'hanka'],
        ['mp', '(.*m)pA', 'kampa'],
        ['nt', '(.*n)tA', 'kanta'],
        ['pp', '(.*p)pA', 'kappa'],
        ['rt', '(.*r)tA', 'parta'],
        ['lt', '(.*l)tA', 'valta'],
        ['kk', '(.*k)kA', 'haka'],
        ['p', '(.*)pA', 'napa'],
        ['t', '(.*)tA', 'pata'],
        ['k>j', '(.*A)ikA', 'aika'],
        ['k>', '(.*AA)kA', 'raaka'],
        ['k>', '(.*V)kA', 'liika'],
        ['k>', '(.*C)kA', 'haka']
    ]],
    ['nahka', '-', [
        [null, '(.*)kA', 'nahka']
    ]],
    ['jumala', '-', [
        [null, '(.*l)A', 'jumala']
    ]],
    ['koira', 'sw', [
        [null, '(.*)A', 'koira'],
        ['tt', '(.*t)tA', 'kenttä'],
        ['nk', '(.*n)kA', 'honka'],
        ['mp', '(.*m)pA', 'kompa'],
        ['nt', '(.*n)tA', 'suunta'],
        ['pp', '(.*p)pA', 'tolppa'],
        ['rt', '(.*r)tA', 'turta'],
        ['lt', '(.*l)tA', 'kulta'],
        ['kk', '(.*k)kA', 'hoikka'],
        ['p', '(.*)pA', 'huopa'],
        ['t', '(.*)tA', 'juhta'],
        ['k>', '(.*i)kA', 'ikä'],
        ['k>', '(.*)kA', 'hoikka']
    ]],
    ['ylkä', '-', [
        [null, '(.*l)kA', 'ylkä']
    ]],
    ['pitkä', '-', [
        [null, '(.*pi)tkA', 'pitkä']
    ]],
    ['ruoka', '-', [
        [null, '(.*ru)oka', 'ruoka']
    ]],
    ['poika', '-', [
        [null, '(.*po)ikA', 'poika']
    ]],
    ['matala', '-', [
        [null, '(.*C)A', 'matala']
    ]],
    ['asema', 'sw', [
        [null, '(.*)A', 'asema'],
        ['tt', '(.*t)tA', 'opotta'],
        ['nt', '(.*n)tA', 'emäntä']
    ]],
    ['kulkija', '-', [
        [null, '(.*i)jA', 'kulkija'],
        [null, '(.*)A', 'apila']
    ]],
    ['video', '-', [
        [null, '(.*deO)', 'video']
    ]],
    ['karahka', 'sw', [
        [null, '(.*)A', 'karahka'],
        ['tt', '(.*t)tA', 'savotta'],
        ['pp', '(.*p)pA', 'ulappa'],
        ['kk', '(.*k)kA', 'solakka'],
        ['nt', '(.*n)tA', 'veranta']
    ]],
    ['apaja', '-', [
        [null, '(.*C)A', 'apaja']
    ]],
    ['peruna', '-', [
        [null, '(.*C)A', 'peruna']
    ]],
    ['korkea', '-', [
        [null, '(.*C)eA', 'korkea'],
        [null, '(.*O)A', 'ainoa']
    ]],
    ['suurempi', 'sw', [
        ['mp', '(.*V)mpi', 'suurempi']
    ]],
    ['vapaa', '-', [
        [null, '(.*CA)A', 'vapaa'],
        [null, '(.*CO)O', 'tienoo'],
        [null, '(.*CU)U', 'leikkuu']
    ]],
    ['kamee', '-', [
        [null, '(.*Ce)e', 'kamee'],
        [null, '(.*CA)A', 'nugaa'],
        [null, '(.*CO)O', 'trikoo'],
        [null, '(.*CU)U', 'revyy']
    ]],
    ['pii', '-', [
        [null, '(.*V)i', 'pii'],
        [null, '(.*A)A', 'maa'],
        [null, '(.*Ce)e', 'tee'],
        [null, '(.*U)U', 'puu']
    ]],
    ['suo', '-', [
        [null, '(.*C)UO', 'suo']
    ]],
    ['askel', 'ws', [
        [null, '(.*VC)', 'askel'],
        ['nn', '(.*n)nel', 'kannel'],
        ['nn', '(.*n)ner', 'kinner'],
        ['nn', '(.*n)nAr', 'piennar'],
        ['mm', '(.*m)mel', 'ommel'],
        ['ng', '(.*n)ger', 'penger'],
        ['d', '(.*)dAr', 'udar'],
        ['v', '(.*)vAl', 'taival'],
        ['>k', '(.*)en', 'säen']
    ]],
    ['rosé', '-', [
        [null, '(.*V)', 'rosé']
    ]],
    ['spray', '-', [
        [null, '(.*[ao]y)', 'spray']
    ]],
    ['parfait', '-', [
        [null, '(.*)', 'parfait']
    ]],
    ['huuli', '-', [
        [null, '(.*C)i', 'tuohi']
    ]],
    ['meri', '-', [
        [null, '(.*er)i', 'meri']
    ]],
    ['tuohi', '-', [
        [null, '(.*C)i', 'lohi']
    ]],
    ['niemi', '-', [
        [null, '(.*V)mi', 'niemi']
    ]],
    ['pieni', '-', [
        [null, '(.*n)i', 'pieni']
    ]],
    ['lumi', '-', [
        [null, '(.*V)mi', 'lumi']
    ]],
    ['susi', '-', [
        [null, '(.*V)si', 'susi']
    ]],
    ['tosi', '-', [
        [null, '(.*V)si', 'tosi']
    ]],
    ['kansi', '-', [
        [null, '(.*n)si', 'kansi'],
        [null, '(.*r)si', 'hirsi'],
        [null, '(.*l)si', 'jälsi']
    ]],
    ['sisar', 'ws', [
        [null, '(.*CVC)', 'sisar'],
        ['t', '(.*t)Ar', 'tytär'],
        ['>k', '(.*i)en', 'ien']
    ]],
    ['hapan', '-', [
        [null, '(.*p)An', 'hapan']
    ]],
    ['uistin', 'ws', [
        [null, '(.*[iaä])n', 'uistin'],
        ['nn', '(.*n)nin', 'vaimennin'],
        ['ll', '(.*l)lin', 'sivellin'],
        ['rr', '(.*r)rin', 'kiharrin'],
        ['rr', '(.*r)rOin', 'kerroin'],
        ['d', '(.*)din', 'kaadin'],
        ['v', '(.*)vin', 'kaavin'],
        ['t', '(.*t)in', 'suodatin'],
        ['k', '(.*k)in', 'puin'],
        ['j>k', '(.*l)jin', 'poljin'],
        ['>k', '(.*)in', 'puin']
    ]],
    ['laidun', '-', [
        [null, '(.*)dUn', 'laidun']
    ]],
    ['onneton', 'ws', [
        [null, '(.*t)On', 'alaston'],
        ['t', '(.*t)On', 'onneton']
    ]],
    ['lämmin', '-', [
        [null, '(.*m)min', 'lämmin']
    ]],
    ['vasen', '-', [
        [null, '(.*e)n', 'vasen']
    ]],
    ['sisin', '', [
        [null, '(.*)in', 'pahin']
    ]],
    ['nainen', '-', [
        [null, '(.*)nen', 'nainen']
    ]],
    ['vastaus', '-', [
        [null, '(.*V)s', 'vastaus']
    ]],
    ['kalleus', '-', [
        [null, '(.*VU)s', 'kalleus'],
        [null, '(.*vU)s', 'kalleus']
    ]],
    ['kaunis', '-', [
        [null, '(.*C)is', 'kaunis']
    ]],
    ['autuas', '-', [
        [null, '(.*U)As', 'autuas']
    ]],
    ['laupias', '-', [
        [null, '(.*p)iAs', 'laupias']
    ]],
    ['vieras', 'ws', [
        [null, '(.*[lmr]i[aä])s', 'antelias'],
        [null, '(.*il[aä])s', 'antelias'],
        [null, '(.*A)s', 'vieras'],
        [null, '(.*)is', 'kauris'],
        [null, '(.*e)s', 'kirves'],
        ['nn', '(.*n)nAs', 'kinnas'],
        ['ll', '(.*l)lAs', 'allas'],
        ['rr', '(.*r)rAs', 'harras'],
        ['mm', '(.*m)mAs', 'hammas'],
        ['ng', '(.*n)gAs', 'kangas'],
        ['k', '(.*k)As', 'avokas', [SUBST]],
        ['k', '(.*k)As', 'vilkas', [ADJ]],
        ['p', '(.*p)As', 'saapas'],
        ['d', '(.*)dAs', 'ahdas'],
        ['v', '(.*)vAs', 'varvas'],
        ['t', '(.*t)As', 'ratas'],
        ['t', '(.*t)is', 'altis'],
        ['>k', '(.*)As', 'varas'],
        ['>k', '(.*)is', 'ruis'],
        ['>k', '(.*)es', 'ies']
    ]],
    ['iäkäs', 'ws', [
        ['k', '(.*k)As', 'iäkäs', [ADJ]],
        ['k', '(.*k)As', 'asiakas', [SUBST]]
    ]],
    ['ohut', '-', [
        [null, '(.*CU)t', 'airut']
    ]],
    ['kevät', '-', [
        [null, '(.*A)t', 'kevät']
    ]],
    ['mies', '-', [
        [null, '(.*mie)s', 'mies']
    ]],
    ['kuollut', '-', [
        [null, '(.*C)Ut', 'kuollut']
    ]],
    ['hame', 'ws', [
        [null, '(.*e)', 'hame'],
        ['nn', '(.*n)ne', 'enne'],
        ['ll', '(.*l)le', 'helle'],
        ['rr', '(.*r)re', 'kierre'],
        ['mm', '(.*m)me', 'lumme'],
        ['j>k', '(.*C)je', 'lahje'],
        ['p', '(.*p)e', 'lape'],
        ['d', '(.*)de', 'sade'],
        ['v', '(.*)ve', 'taive'],
        ['k', '(.*k)e', 'tarvike'],
        ['>k', '(.*V)e', 'tarvike'],
        ['>k', '(.*h)e', 'tarvike'],
        ['t', '(.*Vt)e', 'vaate'],
        ['t', '(.*lt)e', 'vaate'],
        ['t', '(.*rt)e', 'vaate']
    ]],
    ['alkeet', '-', [
        [null, '(.*ke)et', 'alkeet']
    ]],
    ['tie', '-', [
        [null, '(.*t)ie', 'tie']
    ]],
    ['lapsi', '-', [
        [null, '(.*)psi', 'lapsi']
    ]],
    ['hapsi', '-', [
        [null, '(.*)psi', 'hapsi']
    ]],
    ['loppu', '-', [
        [null, '(.*)', 'loppu']
    ]],
    ['veitsi', '-', [
        [null, '(.*)tsi', 'veitsi']
    ]],
    ['kantaja', '-', [
        [null, '(.*j)A', 'kantaja']
    ]],
    ['koiras', '-', [
        [null, '(.*)s', 'koiras']
    ]],
];



// https://github.com/voikko/corevoikko/blob/master/tools/pylib/voikkoinfl.py#L426

function compileClassmapREs($inputClassmap) {
    /**
     * Converts a classmap to a form where regular expressions have been
     * compiled to regular expression objects
     */
    $outputClassmap = [];

    foreach ($inputClassmap as $joClass) {
        $ruleList = [];

        foreach ($joClass[2] as $inputRule) {
            $pattern = $inputRule[1];
            $pattern = str_replace('V', '(?:a|á|e|i|o|u|y|ä|ö|é)', $pattern);
            $pattern = str_replace('C', '(?:b|c|d|f|g|h|j|k|l|m|n|p|q|r|s|t|v|w|x|y|z|š|ž)', $pattern);
            $pattern = str_replace('A', '(?:a|ä)', $pattern);
            $pattern = str_replace('O', '(?:o|ö)', $pattern);
            $pattern = str_replace('U', '(?:u|y)', $pattern);
            $regExp = '/^' . $pattern . '$/i';

            $outputRule = [$inputRule[0], $regExp, $inputRule[2]];

            if (count($inputRule) === 4) {
                $outputRule[] = $inputRule[3];
            }

            $ruleList[] = $outputRule;
        }

        $outputClassmap[] = [$joClass[0], $joClass[1], $ruleList];
    }

    return $outputClassmap;
}


function match_re($str, $regExp) {
    if (preg_match($regExp, $str, $matches)) {
        return $matches[1];
    } else {
        return null;
    }
}

# Translates word match pattern to a Perl-compatible regular expression
function wordPatternToPCRE($pattern) {
    return '.*' . capitalCharRegexp($pattern) . '$';
}

function capitalCharRegexp($pattern) {
    $pattern = str_replace('V', '(?:a|e|i|o|u|y|ä|ö|é|è|á|ó|â)', $pattern);
    $pattern = str_replace('C', '(?:b|c|d|f|g|h|j|k|l|m|n|p|q|r|s|t|v|w|x|z|š|ž)', $pattern);
    $pattern = str_replace('A', '(?:a|ä)', $pattern);
    $pattern = str_replace('O', '(?:o|ö)', $pattern);
    $pattern = str_replace('U', '(?:u|y)', $pattern);
    return $pattern;
}

function simpleVowelType($word) {
    $word = strtolower($word);

    $lastBack = max(strrpos($word, 'a') ?: -1, strrpos($word, 'o') ?: -1, strrpos($word, 'å') ?: -1, strrpos($word, 'u') ?: -1);
    $lastOrdFront = max(strrpos($word, 'ä') ?: -1, strrpos($word, 'ö') ?: -1);
    $lastY = strrpos($word, 'y') ?: -1;

    if ($lastBack > -1 && max($lastOrdFront, $lastY) == -1) {
        return VOWEL_BACK;
    }
    if ($lastBack == -1 && max($lastOrdFront, $lastY) > -1) {
        return VOWEL_FRONT;
    }
    if (max($lastBack, $lastOrdFront, $lastY) == -1) {
        return VOWEL_FRONT;
    }
    if ($lastY < max($lastBack, $lastOrdFront)) {
        return ($lastBack > $lastOrdFront) ? VOWEL_BACK : VOWEL_FRONT;
    } else {
        return VOWEL_BOTH;
    }
}

function getWordformInflVowelType($wordform)
{
    // Search for last '=' or '-', check the trailing part using recursion
    $startind = max(strrpos($wordform, '='), strrpos($wordform, '-'));
    if ($startind === strlen($wordform) - 1) {
        return VOWEL_BOTH;
    }
    if ($startind !== false) {
        return getWordformInflVowelType(substr($wordform, $startind + 1));
    }

    // Search for first '|', check the trailing part using recursion
    $startind = strpos($wordform, '|');
    if ($startind === strlen($wordform) - 1) {
        return VOWEL_BOTH;
    }
    $vtypeWhole = simpleVowelType($wordform);
    if ($startind === false) {
        return $vtypeWhole;
    }
    $vtypePart = getWordformInflVowelType(substr($wordform, $startind + 1));

    return ($vtypeWhole === $vtypePart) ? $vtypeWhole : VOWEL_BOTH;
}

function regexToHunspell($exp, $repl) {
    $ruleList = [];
    $wChars = "[a-zäöé]";

    if ($exp === "" || $exp === null) $exp = "0";
    if ($repl === "") $repl = "0";

    if ($exp === "0") {
        $stripStr = "0";
        $condition = ".";
        $affix = $repl;
        $ruleList[] = [$stripStr, $affix, $condition];
        return $ruleList;
    }
    if (preg_match("/^(?:$wChars)+$/", $exp)) { // String of letters
        $stripStr = $exp;
        $condition = $exp;
        $affix = $repl;
        $ruleList[] = [$stripStr, $affix, $condition];
        return $ruleList;
    }

    $pattern = sprintf("/^((?:%s)*)\\(\\[((?:%s)*)\\]\\)((?:%s)*)$/u", $wChars, $wChars, $wChars);
    if (preg_match($pattern, $exp, $matches)) {
        // Exp is of form 'ab([cd])ef'
        $startLetters = $matches[1];
        $altLetters = $matches[2];
        $endLetters = $matches[3];

        foreach (mb_str_split($altLetters) as $altChar) {
            $stripStr = $startLetters . $altChar . $endLetters;
            $condition = $startLetters . $altChar . $endLetters;
            $affix = str_replace('(1)', $altChar, $repl);
            $ruleList[] = [$stripStr, $affix, $condition];
        }
        return $ruleList;
    }

    $pattern = sprintf("/^((?:%s)*)\\[((?:%s)*)\\]((?:%s)*)$/u", $wChars, $wChars, $wChars);

    if (preg_match($pattern, $exp, $matches)) {
        // Exp is of form 'ab[cd]ef'
        $startLetters = $matches[1];
        $altLetters = $matches[2];
        $endLetters = $matches[3];

        foreach (mb_str_split($altLetters) as $altChar) {
            $stripStr = $startLetters . $altChar . $endLetters;
            $condition = $startLetters . $altChar . $endLetters;
            $affix = $repl;
            $ruleList[] = [$stripStr, $affix, $condition];
        }
        return $ruleList;
    }

    echo "Unsupported regular expression: exp='$exp', repl='$repl'\n";
    return [];
}

function replaceConditionalApostrophe($word) {
    $ind = strpos($word, '$');
    if ($ind === false) return $word;
    if ($ind == 0 || $ind == strlen($word) - 1) return str_replace('$', '', $word);

    if ($word[$ind - 1] == $word[$ind + 1]) {
        if (in_array($word[$ind - 1], ['i', 'o', 'u', 'y', 'ö'])) {
            return str_replace('$', '\'', $word);
        }
        if (in_array($word[$ind - 1], ['a', 'ä']) && $ind > 1 && $word[$ind - 2] == $word[$ind - 1]) {
            return str_replace('$', '\'', $word);
        }
    }

    return str_replace('$', '', $word);
}


class InflectedWord {
    public $formName;
    public $isCharacteristic;
    public $priority;
    public $inflectedWord;
}

class InflectionRule {
    public $name;
    public $isCharacteristic;
    public $delSuffix;
    public $addSuffix;
    public $gradation;
    public $rulePriority;
}
class InflectionType {
    public $kotusClasses;
    public $joukahainenClasses;
    public $gradation;
    public $matchWord;
    public $rmsfx;
    public array $inflectionRules;
    public $note;

    public function removeSuffix(string $word): string
    {
        if (!isset($this->rmsfx)) {
            return $word;
        }
        $l = strlen($this->rmsfx);

        if ($l == 0) {
            return $word;
        } elseif (strlen($word) <= $l) {
            return "";
        } else {
            return substr($word, 0, -$l);
        }
    }
}

function inflectWordWithType(
    string $word,
    InflectionType $inflection_type,
    $infclass,
    $gradclass,
    $vowel_type = VOWEL_DEFAULT
) {
    if ($inflection_type->joukahainenClasses == null) {
        return [];
    }
    if (!in_array($infclass, $inflection_type->joukahainenClasses)) return [];

    $word_no_sfx = $inflection_type->removeSuffix($word);
    $word_grad = applyGradation($word_no_sfx, $gradclass);
    if ($word_grad === null) return [];

    if ($gradclass === '-') {
        $grad_type = GRAD_NONE;
    } elseif (in_array($gradclass, ['av1', 'av3', 'av5'])) {
        $grad_type = GRAD_SW;
    } elseif (in_array($gradclass, ['av2', 'av4', 'av6'])) {
        $grad_type = GRAD_WS;
    }

    if ($grad_type !== GRAD_NONE && $grad_type !== $inflection_type->gradation) return [];

    if (!preg_match("/" . wordPatternToPCRE($inflection_type->matchWord) . "/i", $word)) return [];

    $inflection_list = [];
    if ($vowel_type === VOWEL_DEFAULT) {
        $vowel_type = getWordformInflVowelType($word);
    }

    foreach ($inflection_type->inflectionRules as $rule) {
        $word_base = ($rule->gradation === GRAD_STRONG) ? $word_grad[0] : $word_grad[1];
        $hunspell_rules = regexToHunspell($rule->delSuffix, $rule->addSuffix);
        foreach ($hunspell_rules as $hunspell_rule) {
            $word_stripped_base = ($hunspell_rule[0] === '0') ? $word_base : substr($word_base, 0, -strlen($hunspell_rule[0]));
            $affix = ($hunspell_rule[1] === '0') ? '' : $hunspell_rule[1];
            $pattern = ($hunspell_rule[2] === '.') ? '' : $hunspell_rule[2];

            $infl = new InflectedWord();
            $infl->formName = $rule->name;
            $infl->isCharacteristic = $rule->isCharacteristic;
            $infl->priority = $rule->rulePriority;

            $vowel_harmony_rule = null;
            if (in_array($rule->name, ['subst_tO', 'subst_Os'])) {
                $vowel_harmony_rule = "vtypeSpecialClass1";
            } elseif (in_array($rule->name, ['verbi_AjAA', 'verbi_AhtAA', 'verbi_AUttAA', 'verbi_AUtellA'])) {
                $vowel_harmony_rule = "vtypeSpecialClass2";
            } elseif ($rule->name === 'partitiivi' && $infclass === 'meri') {
                $vowel_harmony_rule = "vtypeMeriPartitive";
            }

            $final_base = str_replace(["=", "|"], "", $word_stripped_base);
            if ($vowel_harmony_rule !== null) {
                if (call_user_func($vowel_harmony_rule, $word_stripped_base) === VOWEL_FRONT) {
                    $infl->inflectedWord = $final_base . convertTvEv($affix);
                } else {
                    $infl->inflectedWord = $final_base . $affix;
                }
                $inflection_list[] = $infl;
                continue;
            }

            if (in_array($vowel_type, [VOWEL_BACK, VOWEL_BOTH]) &&
                str_ends_with($word_base, $pattern)) {
                $infl->inflectedWord = replaceConditionalApostrophe($final_base . $affix);
                $inflection_list[] = $infl;

                $infl = new InflectedWord();
                $infl->formName = $rule->name;
                $infl->isCharacteristic = $rule->isCharacteristic;
                $infl->priority = $rule->rulePriority;
            }

            if (in_array($vowel_type, [VOWEL_FRONT, VOWEL_BOTH]) &&
                str_ends_with($word_base, convertTvEv($pattern))) {
                $infl->inflectedWord = replaceConditionalApostrophe($final_base . convertTvEv($affix));
                $inflection_list[] = $infl;
            }
        }
    }
    return $inflection_list;
}



function inflectWord($word, $classes) {
    $noun_types = readInflectionTypes("data.aff");

    list($infclass) = wordAndInflClass($classes);

    $itypes = $noun_types;

    foreach (inflectAWord($word, $infclass, $itypes) as $iword) {
        print ($iword->formName . " " . $iword->inflectedWord . "\n");
    }
}

function wordAndInflClass($fullclass) {
    $infclass_parts = explode('-', $fullclass);
    if (count($infclass_parts) == 2) {
        $wordclass = $infclass_parts[0];
        $infclass = $infclass_parts[1];
    } elseif (count($infclass_parts) == 3) {
        $wordclass = $infclass_parts[0];
        $infclass = $infclass_parts[1] . '-' . $infclass_parts[2];
    } else {
        die('Incorrect inflection class');
    }

    if (!in_array($wordclass, ['subst', 'verbi'])) {
        die('Incorrect word class');
    }

    return [$infclass];
}

function inflectAWord($word, $jo_infclass, $inflection_types) {
    $dash = strpos($jo_infclass, '-');
    if ($dash === false) {
        $infclass = $jo_infclass;
        $gradclass = '-';
    } else {
        $infclass = substr($jo_infclass, 0, $dash);
        $gradclass = substr($jo_infclass, $dash + 1);

        if (!in_array($gradclass, ['av1', 'av2', 'av3', 'av4', 'av5', 'av6', '-'])) {
            return [];
        }
    }
    foreach ($inflection_types as $inflection_type) {
        $inflection = inflectWordWithType($word, $inflection_type, $infclass, $gradclass, VOWEL_DEFAULT);
        if (!empty($inflection)) {
            return $inflection;
        }
    }
    return [];
}

function convertTvEv($pattern) {
    return str_replace(['a', 'o', 'u'], ['ä', 'ö', 'y'], $pattern);
}

function applyGradation($word, $gradType) {
    if ($gradType == '-') {
        return [$word, $word];
    }

    if (isConsonant(substr($word, -1)) && !isConsonant(substr($word, -2, 1)) && strlen($word) >= 3) {
        if (substr($word, -4, 2) == 'ng') {
            return [substr($word, 0, -4) . 'nk' . substr($word, -2), $word];
        }
        if (substr($word, -4, 2) == 'mm') {
            return [substr($word, 0, -4) . 'mp' . substr($word, -2), $word];
        }
        if (substr($word, -4, 2) == 'nn') {
            return [substr($word, 0, -4) . 'nt' . substr($word, -2), $word];
        }
        if (substr($word, -4, 2) == 'll') {
            return [substr($word, 0, -4) . 'lt' . substr($word, -2), $word];
        }
        if (substr($word, -4, 2) == 'rr') {
            return [substr($word, 0, -4) . 'rt' . substr($word, -2), $word];
        }
        if (substr($word, -3, 1) == 'd') {
            return [substr($word, 0, -3) . 't' . substr($word, -2), $word];
        }
        if (in_array(substr($word, -3, 1), ['t', 'k', 'p'])) {
            return [substr($word, 0, -2) . substr($word, -3), $word];
        }
        if (substr($word, -3, 1) == 'v') {
            return [substr($word, 0, -3) . 'p' . substr($word, -2), $word];
        }
    }

    if ($gradType == 'av1' && strlen($word) >= 3) {
        if (in_array(substr($word, -3, 2), ['tt', 'kk', 'pp'])) {
            return [$word, substr($word, 0, -2) . substr($word, -1)];
        }
        if (substr($word, -3, 2) == 'mp') {
            return [$word, substr($word, 0, -3) . 'mm' . substr($word, -1)];
        }
        if (substr($word, -2, 1) == 'p' && !isConsonant(substr($word, -1))) {
            return [$word, substr($word, 0, -2) . 'v' . substr($word, -1)];
        }
        if (substr($word, -3, 2) == 'nt') {
            return [$word, substr($word, 0, -3) . 'nn' . substr($word, -1)];
        }
        if (substr($word, -3, 2) == 'lt') {
            return [$word, substr($word, 0, -3) . 'll' . substr($word, -1)];
        }
        if (substr($word, -3, 2) == 'rt') {
            return [$word, substr($word, 0, -3) . 'rr' . substr($word, -1)];
        }
        if (substr($word, -2, 1) == 't') {
            return [$word, substr($word, 0, -2) . 'd' . substr($word, -1)];
        }
    }

    if ($gradType == 'av3' && strlen($word) >= 3 && substr($word, -2, 1) == 'k') {
        if (isConsonant(substr($word, -3, 1))) {
            return [$word, substr($word, 0, -2) . 'j' . substr($word, -1)];
        } else {
            return [$word, substr($word, 0, -3) . 'j' . substr($word, -1)];
        }
    }

    if ($gradType == 'av5' && strlen($word) >= 2 && substr($word, -2, 1) == 'k') {
        return [$word, substr($word, 0, -2) . '$' . substr($word, -1)];
    }

    if ($gradType == 'av6' && strlen($word) >= 1) {
        if (isConsonant(substr($word, -1))) {
            return [substr($word, 0, -2) . 'k' . substr($word, -2), $word];
        } else {
            return [substr($word, 0, -1) . 'k' . substr($word, -1), $word];
        }
    }

    return null;
}

function isConsonant($char) {
    return preg_match('/[bcdfghjklmnpqrstvwxyz]/i', $char);
}


function readInflectionTypes($file_name) {
    $inflection_types = [];
    $inflection_types = __read_inflection_type($file_name);
    return $inflection_types;
}

function getNextLine($file) {
    do {
        $line = fgets($file);
        if (str_starts_with($line, '#')) {
            $line = "";
        } else {
            $line = trim($line);
        }
    } while ($line !== false && $line === '');
    return $line;
}

/**
 * @param $file
 * @return InflectionType[] array
 */
function __read_inflection_type($file): array
{
    $result = [];
    $lines = [];
    $c = file_get_contents($file);
    $p = explode("\n", $c);
    foreach ($p as $line) {
        if (str_starts_with($line, '#')) {
            $line = "";
        } else {
            $line = trim($line);
        }
        if ($line !== '') {
            $lines[] = $line;
        }
    }

    foreach ($lines as $line) {
        $header_tuple = explode(":", $line);
        $header_tuple[0] = trim($header_tuple[0]);

        if (count($header_tuple) >= 2) {
            $header_tuple[1] = trim($header_tuple[1]);
            if (count($header_tuple) > 2) {
                $header_tuple[1] .= ":".$header_tuple[2];
            }
            if (str_contains($header_tuple[1], "#")) {
                die($header_tuple[1]);
            }
        }
        switch ($header_tuple[0]) {
            case 'class':
                $t = new InflectionType();
                $t->kotusClasses = explode(",", $header_tuple[1]);
                break;
            case 'sm-class':
                $t->joukahainenClasses = explode(" ", $header_tuple[1]);
                break;
            case 'rmsfx':
                $t->rmsfx = $header_tuple[1];
                break;
            case 'match-word':
                $t->matchWord = $header_tuple[1];
                break;
            case 'consonant-gradation':
                if ($header_tuple[1] === '-') $t->gradation = GRAD_NONE;
                if ($header_tuple[1] === 'sw') $t->gradation = GRAD_SW;
                if ($header_tuple[1] === 'ws') $t->gradation = GRAD_WS;
                break;
            case 'note':
                $t->note = $header_tuple[1];
                break;
            case 'end':
                if ($header_tuple[1] === 'class') {
                    $result[] = $t;
                    $t = new InflectionType();
                } else if ($header_tuple[1] === 'rules') {

                }
                break;
            case 'rules':
                break;
            case 'group':
            case 'transform-group':
                // skip these
                break;
            default:
                $rule = new InflectionRule();
                $strippedLine = trim($line);
                $columns = preg_split('/\s+/', $strippedLine);

                if (str_starts_with($columns[0], '!')) {
                    $rule->name = substr($columns[0], 1);
                    $rule->isCharacteristic = true;
                } else {
                    $rule->name = $columns[0];
                    $rule->isCharacteristic = in_array($columns[0], [
                        'nominatiivi', 'genetiivi', 'partitiivi', 'illatiivi',
                        'nominatiivi_mon', 'genetiivi_mon', 'partitiivi_mon', 'illatiivi_mon',
                        'infinitiivi_1', 'preesens_yks_1', 'imperfekti_yks_3',
                        'kondit_yks_3', 'imperatiivi_yks_3', 'partisiippi_2',
                        'imperfekti_pass'
                    ]);
                }

                if ($columns[1] !== '0') {
                    $rule->delSuffix = $columns[1];
                }
                if ($columns[2] !== '0') {
                    $rule->addSuffix = $columns[2];
                }
                if ($columns[3] === 's') {
                    $rule->gradation = GRAD_STRONG;
                }
                if (count($columns) > 4) {
                    if (__read_option($columns[4], 'ps', '') === 'r') {
                        // skip this
                    } else {
                        $rule->rulePriority = (int) __read_option($columns[4], 'prio', '1');
                    }
                }
                $t->inflectionRules[] = $rule;
                break;
        }
    }

    return $result;
}


function __read_option($options, $name, $default) : string
{
    $parts = explode(',', $options);

    foreach ($parts as $part) {
        $nameval = explode('=', $part);

        if (count($nameval) === 2 && $nameval[0] === $name) {
            return $nameval[1];
        }
        if (count($nameval) === 1 && $nameval[0] === $name) {
            return '1';
        }
    }

    return $default;
}












// inflectWord("makkara", "subst-kulkija");
inflectWord("folaatti", "subst-risti-av1");

