<?php

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

function inflectWordWithType(
    string $word,
    InflectionType $inflection_type,
    string $infclass,
    string $gradclass,
    int $vowel_type = VOWEL_DEFAULT
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
            if ($pattern == null) {
                $pattern = "";
            }
            if ($affix == null) {
                $affix = "";
            }

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

function normalizeBase($base) {
    $pos = strpos($base, '=');
    if ($pos !== false) {
        $base = substr($base, $pos + 1);
    }
    return strtolower($base);
}


function vtypeSpecialClass1($base) {
    $base = normalizeBase($base);
    $lastBack = max(strrpos($base, 'a'), strrpos($base, 'o'), strrpos($base, 'å'), strrpos($base, 'u'));
    $lastFront = max(strrpos($base, 'ä'), strrpos($base, 'ö'), strrpos($base, 'y'));

    if ($lastFront > $lastBack) {
        return VOWEL_FRONT;
    } else {
        return VOWEL_BACK;
    }
}

function vtypeSpecialClass2($base) {
    $base = normalizeBase($base);

    $lastBack = max(strrpos($base, 'a'), strrpos($base, 'o'), strrpos($base, 'å'), strrpos($base, 'u'));
    $lastFront = max(strrpos($base, 'ä'), strrpos($base, 'ö'), strrpos($base, 'y'));

    if ($lastFront > $lastBack) {
        return VOWEL_FRONT;
    } elseif ($lastFront < $lastBack) {
        return VOWEL_BACK;
    } else {
        // No front or back vowels
        if (strrpos($base, 'e') !== false) {
            // "hel|istä" -> "heläjää"
            return VOWEL_FRONT;
        } else {
            // "kih|istä" -> "kihajaa"
            return VOWEL_BACK;
        }
    }
}
function vtypeMeriPartitive($base) {
    return VOWEL_BACK;
}


function inflectWord($word, $classes) {
    $noun_types = readInflectionTypes("data.aff");

    $classes = wordAndInflClass($classes);
    $infclass = $classes["infclass"];
    $av = $classes["av"];

    foreach (inflectAWord($word, $infclass, $av, $noun_types) as $iword) {
        print ($iword->formName . " " . $iword->inflectedWord . "\n");
    }
}

/**
 * @param $fullclass subst-risti-av1
 * @return string[] "risti", "av1"
 * @throws Exception
 */
function wordAndInflClass($fullclass): array
{
    $av = "-";
    $infclass_parts = explode('-', $fullclass);
    if (count($infclass_parts) <= 1) {
        throw new Exception('Incorrect inflection class');
    } elseif (count($infclass_parts) == 3) {
        $av = $infclass_parts[2];
    }
    $wordclass = $infclass_parts[0];

    if (!in_array($wordclass, ['subst', 'verbi'])) {
        throw new Exception('Incorrect word class');
    }

    return ["infclass" => $infclass_parts[1], "av" => $av];
}

function inflectAWord(string $word, string $infclass, string $gradclass, array $inflection_types): array
{
    if (!in_array($gradclass, ['av1', 'av2', 'av3', 'av4', 'av5', 'av6', '-'])) {
        return [];
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


/**
 * @param $file
 * @return InflectionType[] array
 */
function readInflectionTypes($file): array
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

function utf8_strrev($str){
    preg_match_all('/./us', $str, $ar);
    return implode(array_reverse($ar[0]));
}

function doMatch (string $word, string $haystack) {
    if (mb_strlen($word) > 20) {
        $word = mb_substr($word, -20);
    }
    $word = utf8_strrev($word);
    $word .= " ";
    do {
        $word = mb_substr($word, 0, -1);
        $pattern = '/;(' . $word . '[a-zäöå]*);(.*);(.*)\s/';
        preg_match_all($pattern, $haystack, $matches, PREG_PATTERN_ORDER);
        if (mb_strlen($word) <= 1) {
            return "";
        } else if (isset($matches[3][0])) {
            return $matches[3][0];
        }
    } while(true);
}


$c = file_get_contents("jouka.log");
$word = "folaatti";
$word = "tuhat";
$word = "poliisi";
$word = "kivi";
// print (doMatch($word, $c));die();
inflectWord($word, "subst-" . doMatch($word, $c));

// $res = doMatch("edäs", $c);
// inflectWord("makkara", "subst-kulkija");
// inflectWord("folaatti", "subst-risti-av1");

