<?php

namespace Conjugation\Classes;

use Conjugation\Enums\GradType;
use Conjugation\Enums\VowelType;
use Conjugation\Helpers\ConjugateWord;

class Noun {

    public array $nounTypes;

    public function __construct()
    {
        $inflection = new Inflection();
        $this->nounTypes = $inflection->nounTypes;
    }


    public function inflectWord(string $word, string $formName = "nominatiivi"): string
    {
        $result = $this->inflectWordAllForms($word);
        if (isset($result[$formName])) {
            return $result[$formName];
        }
        return $word;
    }

    /**
     * @param $word
     * @return array<string, string>
     */
    public function inflectWordAllForms(string $word): array
    {
        $result = [];

        $conjugateBasedOn = new ConjugateWord($word);
        $infclass = $conjugateBasedOn->infclass;
        $av = $conjugateBasedOn->av;
        foreach ($this->inflectAWord($word, $infclass, $av, $this->nounTypes) as $iword) {
            if (!isset($result[$iword->formName])) {
                $result[$iword->formName] = trim($iword->inflectedWord);
            }
        }
        return $result;
    }

    public function inflectAWord(string $word, string $infclass, string $gradclass, array $inflection_types): array
    {
        if (!in_array($gradclass, ['av1', 'av2', 'av3', 'av4', 'av5', 'av6', '-'])) {
            return [];
        }
        foreach ($inflection_types as $inflection_type) {
            $inflection = $this->inflectWordWithType($word, $inflection_type, $infclass, $gradclass, VowelType::VOWEL_DEFAULT);
            if (!empty($inflection)) {
                return $inflection;
            }
        }
        return [];
    }

    # Translates word match pattern to a Perl-compatible regular expression
    public function wordPatternToPCRE(string $pattern): string
    {
        return '.*' . $this->capitalCharRegexp($pattern) . '$';
    }

    public function capitalCharRegexp(string $pattern): string
    {
        $pattern = str_replace('V', '(?:a|e|i|o|u|y|ä|ö|é|è|á|ó|â)', $pattern);
        $pattern = str_replace('C', '(?:b|c|d|f|g|h|j|k|l|m|n|p|q|r|s|t|v|w|x|z|š|ž)', $pattern);
        $pattern = str_replace('A', '(?:a|ä)', $pattern);
        $pattern = str_replace('O', '(?:o|ö)', $pattern);
        $pattern = str_replace('U', '(?:u|y)', $pattern);
        return $pattern;
    }

    public function simpleVowelType(string $word): int
    {
        $word = strtolower($word);

        $lastBack = max(mb_strrpos($word, 'a') ?: -1, mb_strrpos($word, 'o') ?: -1, mb_strrpos($word, 'å') ?: -1, mb_strrpos($word, 'u') ?: -1);
        $lastOrdFront = max(mb_strrpos($word, 'ä') ?: -1, mb_strrpos($word, 'ö') ?: -1);
        $lastY = mb_strrpos($word, 'y') ?: -1;

        if ($lastBack > -1 && max($lastOrdFront, $lastY) == -1) {
            return VowelType::VOWEL_BACK;
        }
        if ($lastBack == -1 && max($lastOrdFront, $lastY) > -1) {
            return VowelType::VOWEL_FRONT;
        }
        if (max($lastBack, $lastOrdFront, $lastY) == -1) {
            return VowelType::VOWEL_FRONT;
        }
        if ($lastY < max($lastBack, $lastOrdFront)) {
            return ($lastBack > $lastOrdFront) ? VowelType::VOWEL_BACK : VowelType::VOWEL_FRONT;
        } else {
            return VowelType::VOWEL_BOTH;
        }
    }

    public function getWordformInflVowelType(string $wordform): int
    {
        // Search for last '=' or '-', check the trailing part using recursion
        $startind = max(mb_strrpos($wordform, '='), mb_strrpos($wordform, '-'));
        if ($startind === mb_strlen($wordform) - 1) {
            return VowelType::VOWEL_BOTH;
        }
        if ($startind !== false) {
            return $this->getWordformInflVowelType(mb_substr($wordform, $startind + 1));
        }

        // Search for first '|', check the trailing part using recursion
        $startind = mb_strpos($wordform, '|');
        if ($startind === mb_strlen($wordform) - 1) {
            return VowelType::VOWEL_BOTH;
        }
        $vtypeWhole = $this->simpleVowelType($wordform);
        if ($startind === false) {
            return $vtypeWhole;
        }
        $vtypePart = $this->getWordformInflVowelType(mb_substr($wordform, $startind + 1));

        return ($vtypeWhole === $vtypePart) ? $vtypeWhole : VowelType::VOWEL_BOTH;
    }

    public function regexToHunspell(?string $exp, ?string $repl): array
    {
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

        return [];
    }

    public function replaceConditionalApostrophe(string $word): string
    {
        $ind = mb_strpos($word, '$');
        if ($ind === false) return $word;
        if ($ind == 0 || $ind == mb_strlen($word) - 1) return str_replace('$', '', $word);

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

    public function inflectWordWithType(
        string $word,
        InflectionType $inflection_type,
        string $infclass,
        string $gradclass,
        int $vowel_type = VowelType::VOWEL_DEFAULT
    ): array {
        if ($inflection_type->joukahainenClasses == null) {
            return [];
        }
        if (!in_array($infclass, $inflection_type->joukahainenClasses)) return [];
        $word_no_sfx = $inflection_type->removeSuffix($word);
        $word_grad = $this->applyGradation($word_no_sfx, $gradclass);
        if ($word_grad === null) return [];

        if ($gradclass === '-') {
            $grad_type = GradType::GRAD_NONE;
        } elseif (in_array($gradclass, ['av1', 'av3', 'av5'])) {
            $grad_type = GradType::GRAD_SW;
        } elseif (in_array($gradclass, ['av2', 'av4', 'av6'])) {
            $grad_type = GradType::GRAD_WS;
        }

        if ($grad_type !== GradType::GRAD_NONE && $grad_type !== $inflection_type->gradation) return [];

        if (!preg_match("/" . $this->wordPatternToPCRE($inflection_type->matchWord) . "/i", $word)) return [];

        $inflection_list = [];
        if ($vowel_type === VowelType::VOWEL_DEFAULT) {
            $vowel_type = $this->getWordformInflVowelType($word);
        }
        foreach ($inflection_type->inflectionRules as $rule) {
            $word_base = ($rule->gradation === GradType::GRAD_STRONG) ? $word_grad[0] : $word_grad[1];
            $hunspell_rules = $this->regexToHunspell($rule->delSuffix, $rule->addSuffix);
            foreach ($hunspell_rules as $hunspell_rule) {
                $word_stripped_base = ($hunspell_rule[0] === '0') ? $word_base : mb_substr($word_base, 0, -mb_strlen($hunspell_rule[0]));
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


                $final_base = str_replace(["=", "|"], "", $word_stripped_base);


                if (in_array($rule->name, ['subst_tO', 'subst_Os'])) {
                    if ($this->vtypeSpecialClass1($word_stripped_base) === VowelType::VOWEL_FRONT) {
                        $infl->inflectedWord = $final_base . $this->convertTvEv($affix);
                    } else {
                        $infl->inflectedWord = $final_base . $affix;
                    }
                    $inflection_list[] = $infl;
                    continue;
                } elseif ($rule->name === 'partitiivi' && $infclass === 'meri') {
                    $infl->inflectedWord = $final_base . $affix;
                    $inflection_list[] = $infl;
                    continue;
                }

                if (in_array($vowel_type, [VowelType::VOWEL_BACK, VowelType::VOWEL_BOTH]) &&
                    str_ends_with($word_base, $pattern)) {
                    $infl->inflectedWord = $this->replaceConditionalApostrophe($final_base . $affix);
                    $inflection_list[] = $infl;

                    $infl = new InflectedWord();
                    $infl->formName = $rule->name;
                    $infl->isCharacteristic = $rule->isCharacteristic;
                    $infl->priority = $rule->rulePriority;
                }

                if (in_array($vowel_type, [VowelType::VOWEL_FRONT, VowelType::VOWEL_BOTH]) &&
                    str_ends_with($word_base, $this->convertTvEv($pattern))) {
                    $infl->inflectedWord = $this->replaceConditionalApostrophe($final_base . $this->convertTvEv($affix));
                    $inflection_list[] = $infl;
                }
            }
        }
        return $inflection_list;
    }

    public function normalizeBase(string $base): string
    {
        $pos = mb_strpos($base, '=');
        if ($pos !== false) {
            $base = mb_substr($base, $pos + 1);
        }
        return mb_strtolower($base);
    }


    public function vtypeSpecialClass1(string $base): int
    {
        $base = $this->normalizeBase($base);
        $lastBack = max(mb_strrpos($base, 'a'), mb_strrpos($base, 'o'), mb_strrpos($base, 'å'), mb_strrpos($base, 'u'));
        $lastFront = max(mb_strrpos($base, 'ä'), mb_strrpos($base, 'ö'), mb_strrpos($base, 'y'));

        if ($lastFront > $lastBack) {
            return VowelType::VOWEL_FRONT;
        } else {
            return VowelType::VOWEL_BACK;
        }
    }

    public function convertTvEv(string $pattern): string
    {
        return str_replace(['a', 'o', 'u'], ['ä', 'ö', 'y'], $pattern);
    }

    public function applyGradation(string $word, string $gradType): ?array
    {
        if ($gradType == '-') {
            return [$word, $word];
        }

        if ($this->isConsonant(mb_substr($word, -1)) && !$this->isConsonant(mb_substr($word, -2, 1)) && mb_strlen($word) >= 3) {
            if (mb_substr($word, -4, 2) == 'ng') {
                return [mb_substr($word, 0, -4) . 'nk' . mb_substr($word, -2), $word];
            }
            if (mb_substr($word, -4, 2) == 'mm') {
                return [mb_substr($word, 0, -4) . 'mp' . mb_substr($word, -2), $word];
            }
            if (mb_substr($word, -4, 2) == 'nn') {
                return [mb_substr($word, 0, -4) . 'nt' . mb_substr($word, -2), $word];
            }
            if (mb_substr($word, -4, 2) == 'll') {
                return [mb_substr($word, 0, -4) . 'lt' . mb_substr($word, -2), $word];
            }
            if (mb_substr($word, -4, 2) == 'rr') {
                return [mb_substr($word, 0, -4) . 'rt' . mb_substr($word, -2), $word];
            }
            if (mb_substr($word, -3, 1) == 'd') {
                return [mb_substr($word, 0, -3) . 't' . mb_substr($word, -2), $word];
            }
            if (in_array(mb_substr($word, -3, 1), ['t', 'k', 'p'])) {
                return [mb_substr($word, 0, -2) . mb_substr($word, -3), $word];
            }
            if (mb_substr($word, -3, 1) == 'v') {
                return [mb_substr($word, 0, -3) . 'p' . mb_substr($word, -2), $word];
            }
        }

        if ($gradType == 'av1' && mb_strlen($word) >= 3) {
            $beforeLastTwo = mb_substr($word, -3, 2);
            $beforeLastOne = mb_substr($word, -2, 1);
            $lastThree = mb_substr($word, -3);

            if (in_array($beforeLastTwo, ['tt', 'kk', 'pp'])) {
                return [$word, mb_substr($word, 0, -2) . mb_substr($word, -1)];
            }
            if ($beforeLastTwo === 'mp') {
                return [$word, mb_substr($word, 0, -3) . 'mm' . mb_substr($word, -1)];
            }
            if ($beforeLastOne === 'p' && !$this->isConsonant(mb_substr($word, -1))) {
                return [$word, mb_substr($word, 0, -2) . 'v' . mb_substr($word, -1)];
            }
            if ($beforeLastTwo === 'nt') {
                return [$word, mb_substr($word, 0, -3) . 'nn' . mb_substr($word, -1)];
            }
            if ($beforeLastTwo === 'lt') {
                return [$word, mb_substr($word, 0, -3) . 'll' . mb_substr($word, -1)];
            }
            if ($beforeLastTwo === 'rt') {
                return [$word, mb_substr($word, 0, -3) . 'rr' . mb_substr($word, -1)];
            }
            if ($beforeLastOne === 't') {
                return [$word, mb_substr($word, 0, -2) . 'd' . mb_substr($word, -1)];
            }
            if ($beforeLastTwo === 'nk') {
                return [$word, mb_substr($word, 0, -3) . 'ng' . mb_substr($word, -1)];
            }
            if ($lastThree === 'uku') {
                return [$word, mb_substr($word, 0, -3) . 'uvu'];
            }
            if ($lastThree === 'yky') {
                return [$word, mb_substr($word, 0, -3) . 'yvy'];
            }
        }

        if ($gradType === 'av2' && mb_strlen($word) >= 2) {
            $beforeLastTwo = mb_substr($word, -3, 2);
            $beforeLastOne = mb_substr($word, -2, 1);

            if ($beforeLastTwo === 'ng') {
                return [mb_substr($word, 0, -3) . 'nk' . mb_substr($word, -1), $word];
            }
            if ($beforeLastTwo === 'mm') {
                return [mb_substr($word, 0, -3) . 'mp' . mb_substr($word, -1), $word];
            }
            if ($beforeLastTwo === 'nn') {
                return [mb_substr($word, 0, -3) . 'nt' . mb_substr($word, -1), $word];
            }
            if ($beforeLastTwo === 'll') {
                return [mb_substr($word, 0, -3) . 'lt' . mb_substr($word, -1), $word];
            }
            if ($beforeLastTwo === 'rr') {
                return [mb_substr($word, 0, -3) . 'rt' . mb_substr($word, -1), $word];
            }
            if ($beforeLastOne === 'd') {
                return [mb_substr($word, 0, -2) . 't' . mb_substr($word, -1), $word];
            }
            if (strpos('tkpbg', $beforeLastOne) !== false) {
                return [mb_substr($word, 0, -1) . mb_substr($word, -2), $word];
            }
            if ($beforeLastOne === 'v') {
                return [mb_substr($word, 0, -2) . 'p' . mb_substr($word, -1), $word];
            }
        }

        if ($gradType == 'av3' && mb_strlen($word) >= 3 && mb_substr($word, -2, 1) == 'k') {
            if ($this->isConsonant(mb_substr($word, -3, 1))) {
                return [$word, mb_substr($word, 0, -2) . 'j' . mb_substr($word, -1)];
            } else {
                return [$word, mb_substr($word, 0, -3) . 'j' . mb_substr($word, -1)];
            }
        }

        if ($gradType == 'av5' && mb_strlen($word) >= 2 && mb_substr($word, -2, 1) == 'k') {
            return [$word, mb_substr($word, 0, -2) . '$' . mb_substr($word, -1)];
        }

        if ($gradType == 'av6' && mb_strlen($word) >= 1) {
            if ($this->isConsonant(mb_substr($word, -1))) {
                return [mb_substr($word, 0, -2) . 'k' . mb_substr($word, -2), $word];
            } else {
                return [mb_substr($word, 0, -1) . 'k' . mb_substr($word, -1), $word];
            }
        }
        return null;
    }

    public function isConsonant(string $char): bool {
        return (preg_match('/[qwrtpsdfghjklzxcvbnm]/i', $char) !== false);
    }
}