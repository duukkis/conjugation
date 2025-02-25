<?php

namespace Conjugation\Helpers;

class ConjugateWord
{

    public string $result;

    /**
     * @param string $word
     * @param string $haystack this is text file with lines like this ;aamhev;vehmaa;vapaa (drow;word;word-to-conjugate-by)
     */
    public function __construct(
        private readonly string $word,
        private readonly string $haystack,
    )
    {
    }

    public function getResult(): string
    {
        $word = $this->word;

        if (mb_strlen($word) > 20) {
            $word = mb_substr($word, -20);
        }
        $word = $this->utf8_strrev($word);
        $umlword = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $word);
        do {
            $this->doWord($word, $this->haystack);
            if ($this->result !== null) {
                return $this->result;
            }
            $word = mb_substr($word, 0, -1);

            $this->doWord($umlword, $this->haystack);
            if ($this->result !== null) {
                return $this->result;
            }
            $umlword = mb_substr($umlword, 0, -1);

            if (mb_strlen($word) <= 1) {
                return "";
            }
        } while(true);
    }

    public function doWord(string $word, string $haystack): void
    {
        $pattern = '/;(' . $word . '[a-zäöå]*);(.*);(.*)\s/';
        preg_match_all($pattern, $haystack, $matches, PREG_PATTERN_ORDER);
        if (isset($matches[3][0])) {
            $this->result = $matches[3][0];
        }
    }

    public function utf8_strrev($str): string
    {
        preg_match_all('/./us', $str, $ar);
        return implode(array_reverse($ar[0]));
    }
}