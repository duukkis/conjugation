<?php

namespace Conjugation\Helpers;

class ConjugateWord
{

    public ?string $infclass = null;
    public ?string $av;
    public string $haystack;

    /**
     * @param string $word
     * @param string $haystack this is text file with lines like this ;aamhev;vehmaa;vapaa (drow;word;word-to-conjugate-by)
     */
    public function __construct(
        private readonly string $word
    )
    {
        $this->haystack = file_get_contents(__DIR__ . "/../../resources/words.txt");
        $this->runDetection();
    }

    public function runDetection(): void
    {
        $word = $this->word;

        if (mb_strlen($word) > 20) {
            $word = mb_substr($word, -20);
        }
        $word = $this->utf8_strrev($word);
        $umlword = str_replace(['ä', 'ö', 'å'], ['a', 'o', 'a'], $word);
        do {
            $this->doWord($word, $this->haystack);
            if ($this->infclass !== null) {
                return;
            }
            $word = mb_substr($word, 0, -1);

            $this->doWord($umlword, $this->haystack);
            if ($this->infclass !== null) {
                return;
            }
            $umlword = mb_substr($umlword, 0, -1);

            if (mb_strlen($word) <= 1) {
                return;
            }
        } while(true);
    }

    public function doWord(string $word, string $haystack): void
    {
        $pattern = '/;(' . $word . '[a-zäöå]*);(.*);(.*)\s/';
        preg_match_all($pattern, $haystack, $matches, PREG_PATTERN_ORDER);
        if (isset($matches[3][0])) {
            $pieces = explode("-", $matches[3][0]);
            $this->infclass = $pieces[0];
            if (isset($pieces[1]) && $pieces[1] !== '') {
                $this->av = $pieces[1];
            } else {
                $this->av = "-";
            }
        }
    }

    public function utf8_strrev($str): string
    {
        preg_match_all('/./us', $str, $ar);
        return implode(array_reverse($ar[0]));
    }
}