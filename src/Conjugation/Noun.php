<?php

namespace Conjugation;

/**
* class to handle all
*/
class Noun
{
    private const WORD_FILE = "words.php";
    private const CACHE_FILE = "words.inc";
  // some helpers if we want to conjugate same word (performance)
    private ?string $word = null;
    private string $bestMatch;
    private ?string $original;
    private array $conjugation;
    private ?bool $backVowelWord;
    private array $backWovels = ["a", "o", "u"];
    private array $frontWovels = ["y", "ä", "ö"];

  // array of words
    public array $words;
  // use this as helper
    public array $indexed_words = [];

  // toista exception is done on function
    public array $numerals = [
        "yksi" => "yhde",
        "kaksi" => "kahde",
        "kolme" => "kolme",
        "neljä" => "neljä",
        "viisi" => "viide",
        "kuusi" => "kuude",
        "seitsemän" => "seitsemä",
        "kahdeksan" => "kahdeksa",
        "yhdeksän" => "yhdeksä",

        "kymmenen|kymmentä" => "kymmene",

        "sataa|sata" => "sada",
        "tuhatta|tuhat" => "tuhanne",

        "miljoonaa|miljoona" => "miljoona",
        "miljardia|miljardi" => "miljardi",
        "biljoonaa|biljoona" => "biljoona",
        "triljoonaa|triljoona" => "triljoona",
      ];

    /**
    * constructor
    * @param boolean $cache - use cache or not
    * @return void
    */
    public function __construct(bool $cache = false)
    {
        if ($cache) {
            if (!$this->getCacheWords()) {
                include(self::WORD_FILE);
                $this->words = $words;
                $this->makeWords();
                $this->setCacheWords();
            }
        } else {
            include(self::WORD_FILE);
            $this->words = $words;
            $this->makeWords();
        }
    }

    /**
    * load words from cache
    * @return boolean - if loading was succesful
    */
    private function getCacheWords(): bool
    {
        $c = @file_get_contents(self::CACHE_FILE);
        if (!empty($c)) {
            $d = unserialize($c);
            if (isset($d["words"]) && !empty($d["words"])) {
                $this->words = $d["words"];
            } else {
                return false;
            }
            if (isset($d["indexed_words"]) && !empty($d["indexed_words"])) {
                $this->indexed_words = $d["indexed_words"];
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * saves words to cache
    * @return void
    */
    private function setCacheWords(): void
    {
        $data = array("words" => $this->words, "indexed_words" => $this->indexed_words);
        @file_put_contents(self::CACHE_FILE, serialize($data));
    }

    /**
    * parse the words, make plurals and place all into indexed_words
    * @return void
    */
    public function makeWords()
    {
      // build the plurals and helper array here
        $singles = [];
        $plurals = [];

        foreach ($this->words as $word => $conjugation) {
    //      $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
    //      $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
    //      $conjugation["si"][0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation["si"][0]);
          // form a plural
            $plural = $this->strRreplace($conjugation[0], $conjugation["si"][0], $word) . "t";
            $plural_postfix = $conjugation["si"][0] . "t";

          // revert the word and set it into the helper
            $drow = $this->utf8Strrev($word);
            $c_temp = array_merge([$conjugation[0]], $conjugation["si"]);
            $this->indexed_words[substr($drow, 0, 1)][$drow] = $c_temp;
            $singles[$word] = $c_temp;

          // add plural also to helper array
            $larulp = $this->utf8Strrev($plural);

            $p_temp = array_merge([$plural_postfix], $conjugation["pl"]);
            $this->indexed_words[substr($larulp, 0, 1)][$larulp] = $p_temp;
          // add the plural to plurals
            $plurals[$plural] = $p_temp;
        }
      // finally add merge the originals and plurals
        $this->words = array_merge($singles, $plurals);
    }

    /**
    * flip a string
    * @param string $str - string to reverse
    * @return string gnirts
    */
    private function utf8Strrev(string $str): string
    {
      // return strrev($str);
        preg_match_all('/./us', $str, $ar);
        return join('', array_reverse($ar[0]));
    }

    /**
    * replace the rightmost occurence of search
    * @param string $search - what to replace
    * @param string $replace - with what
    * @param string $subject - where
    * @return string replaced word
    */
    private function strRreplace(string $search, string $replace, string $subject): string
    {
        $pos = mb_strrpos($subject, $search);
        if ($pos !== false) {
            $subject = mb_substr($subject, 0, $pos) . $replace;
        }
        return $subject;
    }

    /**
    * checks if the word is numeral and conjugates that
    * @param string $word to check
    * @param string $ender what to place at the end n|ksi|lle ...
    * @return ?string null if not a numeral else the conjugated numeral
    */
    private function isNumeral(string $word, string $ender = 'n'): ?string
    {
        $numeral = trim($word);
        $result = trim($word);
        foreach ($this->numerals as $numb => $conj) {
          // do a fast checking by replacing stuff
            $numeral = preg_replace('/' . $numb . '/', '', $numeral);
          // also make the result ready if the word is numeral
            $result = preg_replace('/' . $numb . '/', '' . $conj . $ender . '', $result);
        }
      // toista exception
        $numeral = preg_replace('/toista/', '', $numeral);

        if (empty($numeral)) { // numeral, when all is replaced
            return $result;
        } else {
            return null;
        }
    }

    /**
    * nominative
    */
    public function nominative(string $word): array
    {
        return array("match" => $word, "answer" => $word);
    }

    /**
    * plural
    */
    public function plural(string $word): array
    {
        $this->isBackWovelWord($word); // call this even not used so caching works
        $this->matchWord($word);
        $answer = $this->strRreplace($this->conjugation[0], $this->conjugation[1], $word) . "t";
        return array("match" => $word, "answer" => $answer);
    }

    /**
    * genitive
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function genitive(string $word): array
    {
        $this->isBackWovelWord($word); // call this even not used so caching works
        return $this->conjugateWord($word, "n", 1);
    }

    /**
    * akkusative
    * this is same as nominative/ genetive, so no need to implement, returns the same
    * @param string $word to conjugate
    * @return array "match" with the word given and "answer" with the word
    */
    public function akkusative(string $word): array
    {
        $this->isBackWovelWord($word); // call this even not used so caching works
        return array("match" => $word, "answer" => $word);
    }

    /**
    * partitive
    * -a, -ä, -ta, -tä, -tta, -ttä
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function partitive(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "a", 4);
        } else {
            return $this->conjugateWord($word, "ä", 4);
        }
    }


    /**
    * essive -na, nä
    * THINK tauti - tauteina
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function essive(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "na", 5);
        } else {
            return $this->conjugateWord($word, "nä", 5);
        }
    }

    /**
    * translative
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function translative(string $word): array
    {
        $this->isBackWovelWord($word); // call this even not used so caching works
        return $this->conjugateWord($word, "ksi", 2);
    }

    /**
    * inessive -ssa, -ssä
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function inessive(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "ssa", 2);
        } else {
            return $this->conjugateWord($word, "ssä", 2);
        }
    }

    /**
    * elative -sta, -stä
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function elative(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "sta", 2);
        } else {
            return $this->conjugateWord($word, "stä", 2);
        }
    }

    /**
    * illative
    * 1) Jäätelö putosi hiekka|an. Koputin ove|en. Tulkaa meille kylä|än.
    * Ota lapsi syli|in. Matkustimme kaupunki|in. Luota minu|un. Salama iski talon pääty|yn.
    * 2) Astronautit lensivät kuu|hun ja palasivat takaisin maa|han. Lehmä vajosi suo|hon.
    * Varas pakeni pimeään yö|hön. Tutustuimme kaupungin kirkkoi|hin. Ota yhteyttä mei|hin.
    * 3) Tekisi mieli matkustaa vieraa|seen maahan. Tutustuimme uusiin oppilai|siin
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function illative(string $word): array
    {
        $this->isBackWovelWord($word); // call this even not used so caching works
        return $this->conjugateWord($word, "n", 3);
    }


    /**
    * adessive -lla, -llä
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function adessive(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "lla", 2);
        } else {
            return $this->conjugateWord($word, "llä", 2);
        }
    }

    /**
    * ablatiivi -lta, -ltä
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function ablative(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "lta", 2);
        } else {
            return $this->conjugateWord($word, "ltä", 2);
        }
    }

    /**
    * allative -lle
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function allative(string $word): array
    {
        $this->isBackWovelWord($word); // call this even not used so caching works
        return $this->conjugateWord($word, "lle", 2);
    }

    /**
    * abessive -tta, -ttä
    * @param string $word to conjugate
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function abessive(string $word): array
    {
        if ($this->isBackWovelWord($word)) {
            return $this->conjugateWord($word, "tta", 2);
        } else {
            return $this->conjugateWord($word, "ttä", 2);
        }
    }


    /**
    * check if the word is frontWovel word
    * NOTE: HAS TO BE CALLED BEFORE conjugateWord function
    * @param string $word string to check
    * @return boolean is back wovel
    */
    private function isBackWovelWord(string $word): ?bool
    {
      // if the same word is being checked, use the previous result instead of matching again
        if ($word == $this->word && $this->backVowelWord !== null) {
            return $this->backVowelWord;
        } else {
          // nullify
            $this->backVowelWord = null;
        }

        $backVowelPos = -1;
        foreach ($this->backWovels as $backWovel) {
            $pos = mb_strrpos($word, $backWovel);
            if ($pos !== false && $pos > $backVowelPos) {
                $backVowelPos = $pos;
            }
        }

        $frontVowelPos = -1;
        foreach ($this->frontWovels as $frontWovel) {
            $pos = mb_strrpos($word, $frontWovel);
            if ($pos !== false && $pos > $frontVowelPos) {
                $frontVowelPos = $pos;
            }
        }

        if ($frontVowelPos == -1 && $backVowelPos == -1) { // only i's and e's
            $this->backVowelWord = false;
        } elseif ($backVowelPos >= $frontVowelPos) { // there is a, o or u and no ä, ö or y
            $this->backVowelWord = true;
        } elseif ($backVowelPos <= $frontVowelPos) { // there is  ä, ö or y and no a, o or u
            $this->backVowelWord = false;
        }
        return $this->backVowelWord;
    }

    /**
    * conjugate a word, match to this->words
    * @param string $word to conjugate
    * @param string $ender to add to end of the conjugated word n|ksi|lle|ltä
    * @param int $use_index - what index to use in words
    * @return array "match" with the word that was matched and "answer" with the confugation
    */
    public function conjugateWord(string $word, string $ender, int $use_index): array
    {
      // full match
        $fMatch = $this->fullMatchCheck($word, $use_index, $ender);
        if ($fMatch !== false) {
            $this->word = $word;
            return $fMatch;
        }

      // then check for numeral
        $numeral = $this->isNumeral($word, $ender);
        if ($numeral !== null) {
            $this->word = $word;
            return array("match" => "numeral", "answer" => $numeral);
        }

      // if the word is same as previously, use the existing conjugation and bestMatch
      // otherwise match the word again
        if ($word != $this->word) {
            $this->matchWord($word);
            $this->word = $word;
        }

      // then we have the bast match, just conjugate and exit
        if (
            isset($this->conjugation[0]) && !empty($this->conjugation[0])
            && isset($this->conjugation[$use_index])
        ) {
            if (!empty($this->original)) {
                $word = $this->original;
            }
            $word = $this->strRreplace($this->conjugation[0], $this->conjugation[$use_index], $word) . $ender;
        } else {
            if (!empty($this->original)) {
                $word = $this->original;
            }
            $word = $word . $ender;
        }

        return array("match" => $this->bestMatch, "answer" => $word);
    }

    /**
    * full match checker
    *
    */
    private function fullMatchCheck($word, $use_index, $ender)
    {
        if (isset($this->words[$word])) {
            if (
                isset($this->words[$word][0]) && !empty($this->words[$word][0])
                && isset($this->words[$word][$use_index])
            ) {
                $answer = $this->strRreplace($this->words[$word][0], $this->words[$word][$use_index], $word) . $ender;
            } elseif (isset($this->words[$word][$use_index]) && !empty($this->words[$word][$use_index])) {
                $answer = $word . $this->words[$word][$use_index] . $ender;
            } else {
                $answer = $word . $ender;
            }
            return array("match" => $word, "answer" => $answer);
        }
        return false;
    }

    /**
    * function to match the word
    * @param string $word - word to match
    */
    private function matchWord(string $word)
    {
      // ------- have the original if we have to convert some ä's / a's
        $this->original = null;
      // ------- then check the ao OR äö this is used so that both a and ä conjugate the same
        $auml = false; // is the last one ä/ö and not a/o
        $aumlpos = mb_strrpos($word, "ä");
        $oumlpos = mb_strrpos($word, "ö");
        if ($aumlpos !== false || $oumlpos !== false) {
            $apos = mb_strrpos($word, "a");
            $opos = mb_strrpos($word, "o");
            $this->original = $word;
            $a = -1;
            if ($apos !== false && $opos !== false) {
                $a = max($apos, $opos);
            } elseif ($apos !== false) {
                $a = $apos;
            } elseif ($opos !== false) {
                $a = $opos;
            }
            if ($aumlpos !== false && $oumlpos !== false && max($aumlpos, $oumlpos) > $a) {
                $auml = true;
            } elseif ($aumlpos !== false && $aumlpos > $a) {
                $auml = true;
            } elseif ($oumlpos !== false && $oumlpos > $a) {
                $auml = true;
            }
        }

      // easier to do this with a's and o's than ä's and ö's
        $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
        $drow = $this->utf8Strrev($word);

        $index = mb_substr($drow, 0, 1);

        $wordLength = mb_strlen($word);

        $this->bestMatch = "";
        $bestMatchLetters = 0;
        if (isset($this->indexed_words[$index])) {
            foreach ($this->indexed_words[$index] as $w => $useOnlyKeys) {
                $match = 0;
                $shorterWord = min(mb_strlen($w), $wordLength);
                for ($i = 0; $i < $shorterWord; $i++) {
                    if (mb_substr($w, $i, 1) == mb_substr($drow, $i, 1)) {
                        $match++;
                    } else {
                        $i = 1000; // out of for loop
                    }
                }
              // $useOnlyKeys[0] has the ending which is used in conjugation
              // mehut matches kuollut but does not contain "lut" at the end so > not a best match which should be used
                $partToConjugate = $useOnlyKeys[0];
                if ($match > $bestMatchLetters && mb_substr($word, 0 - strlen($partToConjugate)) == $partToConjugate) {
                    $bestMatchLetters = $match;
                    $this->bestMatch = $w;
                }
            }
            $this->conjugation = $this->indexed_words[$index][$this->bestMatch];
            $this->bestMatch = strrev($this->bestMatch);
        }

      // fix the conjugation with the umlauts
        if (!empty($this->original)) {
            if ($auml) {
              // fix all conjugations
                foreach ($this->conjugation as $k => $v) {
                    $this->conjugation[$k] = str_replace(array("a", "o"), array("ä", "ö"), $this->conjugation[$k]);
                }
            } else {
                foreach ($this->conjugation as $k => $v) {
                    $this->conjugation[$k] = str_replace(array("ä", "ö"), array("a", "o"), $this->conjugation[$k]);
                }
            }
        } elseif (!$auml) {
            foreach ($this->conjugation as $k => $v) {
                $this->conjugation[$k] = str_replace(array("ä", "ö"), array("a", "o"), $this->conjugation[$k]);
            }
        }
    }

    public function newGenetive(string $word, string $ender): string
    {
        $syllabus = new Syllabus($word);
        // example: roi ka le
        $diftong = $syllabus->lastDiftong();                            // roi k[a l]e
        $lastSyllabus = $syllabus->getLastSyllabus();                   // roi ka [le]
        $secLastLetter = $syllabus->secondToLastLetterInLastSyllabus(); // roi ka [l]e
        $firstLetterInLast = $syllabus->firstLetterInLastSyllabus();    // roi ka [l]e
        $lastInSecLast = $syllabus->lastLetterInSecondToLastSyllabus(); // roi k[a] le
        $lastLetter = $syllabus->lastLetter();

        // print $syllabus->lastLetter() . " ";
        switch ($lastLetter) {
            case "a":
            case "o":
            case "u":
            case "y":
                $syllabus = $this->fixAsteVaihtelu($word, $syllabus);
                return $syllabus->returnWord() . $ender;
            case "i":
                $syllabus = $this->fixAsteVaihtelu($word, $syllabus);
                return $syllabus->returnWord() . $ender;
            case "e":
                // tarve
                // terve

                $syllabus = $this->fixAsteVaihtelu($word, $syllabus);

                // e > ee
                if ((!in_array($secLastLetter, ["e", "g", "i"]) || $lastSyllabus == "e") && $syllabus->doubleVowel) {
                    // college, tee, tie
                    $ender = "e" . $ender;
                }
                return $syllabus->returnWord() . $ender;
            default:
                $syllabus = $this->fixAsteVaihteluForConsonant($word, $syllabus);
                $lastLetter = $syllabus->lastLetter();
                if ($syllabus->isVowel($lastLetter) && $syllabus->doubleVowel) {
                    return $syllabus->returnWord() . $lastLetter . $ender;
                } else if ($syllabus->isVowel($lastLetter) && !$syllabus->doubleVowel) {
                    return $syllabus->returnWord() . $ender;
                } else {
                    return $syllabus->returnWord() . "e" . $ender;
                }
        }
    }

    private function fixAsteVaihteluForConsonant(string $word, Syllabus $syllabus): Syllabus
    {
        // example: roi ka le
        $diftong = $syllabus->lastDiftong();                            // roi k[a l]e
        $lastSyllabus = $syllabus->getLastSyllabus();                   // roi ka [le]
        $secToLastSyllabus = $syllabus->getSecondToLastSyllabus();      // roi [ka] le
        $secLastLetter = $syllabus->secondToLastLetterInLastSyllabus(); // roi ka [l]e
        $firstLetterInLast = $syllabus->firstLetterInLastSyllabus();    // roi ka [l]e
        $lastInSecLast = $syllabus->lastLetterInSecondToLastSyllabus(); // roi k[a] le
        $lastLetter = $syllabus->lastLetter();                          // roi ka l[e]
        $twoLastLetters = $secLastLetter . $lastLetter;                 // roi ka [le]

        if ($diftong == "ll") {
            // puhallin
            $syllabus->replaceFirstLetterOfLastSyllabus("t");
        } else if ($lastLetter == "n" && $firstLetterInLast == "t" && in_array($lastInSecLast, ["i", "e", "o", "u", "y"])) {
            // teroitin, keitin
            $syllabus->replaceFirstLetterOfLastSyllabus("tt");
        } else if ($lastSyllabus == "tar") {
            // herttuatar
            $syllabus->replaceFirstLetterOfLastSyllabus("tt");
        }

        if ($lastSyllabus == "mas") {
            // lammas, hammas
            $syllabus->replaceLastSyllabus("pa");
        } else if ($lastSyllabus == "nen") {
            // aakkonen, hetkinen
            $syllabus->replaceLastSyllabus("s");
        } else if ($lastSyllabus == "las") {
            // valas
            $syllabus->replaceLastSyllabus("la");
        } else if ($lastSyllabus == "kas") {
            // rakas
            $syllabus->replaceLastSyllabus("kka");
        } else if ($lastSyllabus == "das") {
            // tehdas, ahdas
            $syllabus->replaceLastSyllabus("ta");
        } else if ($lastSyllabus == "ton") {
            // alaston, onneton
            $syllabus->replaceLastLetter("ma");
            $syllabus->doubleVowel = false;
        } else if ($twoLastLetters == "in") {
            // sisin, teroitin, keitin
            $syllabus->replaceLastLetter("m");
        } else if ($word == "rakkaus") {
            // rakkaus
            $syllabus->replaceLastLetter("d");
        } else if (
            preg_match('/(v|r|k|t|h|l)(au|eu|ey|ou)s$/', $word)
            || preg_match('/uus$/', $word)
            || preg_match('/(i|n)saus$/', $word)
            ) {
            // , ["runsaus", "oikeus", "tiheys"]
            // suotavuus, avaruus, runsaus, oikeus, viisaus, talous
            $syllabus->replaceLastLetter("d");
        } else if (in_array($twoLastLetters, ["us", "os", "ys", "as"])) {
            //   veisaus
            // pilkahdus, pakkaus, kudos, punos, väsymys, lihas, kiusaus
            $syllabus->replaceLastLetter("ks");
        } else if ($lastLetter == "t") {
            // tanssit
            $syllabus->replaceLastLetter("e");
            $syllabus->doubleVowel = false;
        } else if (in_array($twoLastLetters, ["is"])) {
            // kaunis
            $syllabus->removeLastLetter();
        }
        return $syllabus;
    }

    public const iToeBodyWords = [
        "hanki",
        "happi",
        "henki",
        "hirsi",
        "kampi",
        "kampi",
        "kivi",
        "kumpi",
        "kuukausi",
        "kaikki",
        "käsi",
        "kieli",
        "kylki",
        "lahti",
        "lovi",
        "lampi",
        "lahti",
        "lehti",
        "luomi",
        "mieli",
        "mäki",
        "onki",
        "piki",
        "retki",
        "saari",
        "siipi",
        "sampi",
        "susi",
        "suksi",
        "sysi",
        "toimi",
        "tosi",
        "tuuli",
        "veli",
        "vesi",
        "vuori",
        "vuosi",
        "ääni",
    ];

    private function fixAsteVaihtelu(string $word, Syllabus $syllabus): Syllabus
    {
        // example: roi ka le
        $diftong = $syllabus->lastDiftong();                            // roi k[a l]e
        $lastSyllabus = $syllabus->getLastSyllabus();                   // roi ka [le]
        $secToLastSyllabus = $syllabus->getSecondToLastSyllabus();      // roi [ka] le
        // $secLastLetter = $syllabus->secondToLastLetterInLastSyllabus(); // roi ka [l]e
        $firstLetterInLast = $syllabus->firstLetterInLastSyllabus();    // roi ka [l]e
        $lastInSecLast = $syllabus->lastLetterInSecondToLastSyllabus(); // roi k[a] le
        $lastLetter = $syllabus->lastLetter();                          // roi ka l[e]

        if (in_array($word, self::iToeBodyWords)) {
            // Kivi-nominit (148), kieli-nominit (76) ja vesi-nominit (44)
            // tosi, susi, sysi, käsi
            $syllabus->replaceLastLetter("e");
            if ($firstLetterInLast == "s") {
                // susi
                $syllabus->replaceFirstLetterOfLastSyllabus("d");
            }
        }
        //--------------- first the diftongs
        if ($diftong == "hj" && !in_array($word, ["ohje"])) {
            // lahje, pohje
            $syllabus->replaceFirstLetterOfLastSyllabus("k");
        } else if (in_array($diftong, ["kk", "pp"])) {
            // nukke, kukka, lakka, nappi
            $syllabus->removeLastLetterFromSecondToLastSyllabus();
            $syllabus->doubleVowel = false;
        } else if ($diftong == "rr") {
            // piirre
            $syllabus->replaceFirstLetterOfLastSyllabus("t");
        } else if ($diftong == "mp" && in_array($lastLetter, ["a", "i"])) {
            // kampi, kumpi, lampi, rampa
            $syllabus->replaceFirstLetterOfLastSyllabus("m");
        } else if ($diftong == "lt" && in_array($lastLetter, ["a", "o", "u", "i"])) {
            // pelto, valta, multa, pelti
            // NOT polte
            $syllabus->replaceFirstLetterOfLastSyllabus("l");
        } else if ($diftong == "nt" && in_array($lastLetter, ["a", "o", "u", "i"])) {
            // ranta, lanta, sonta, punta, rento, tunti
            $syllabus->replaceFirstLetterOfLastSyllabus("n");
        } else if ($diftong == "rt" && in_array($lastLetter, ["a", "o", "u"])) {
            // parta, virta
            $syllabus->replaceFirstLetterOfLastSyllabus("r");
        } else if ($diftong == "nk") {
            $syllabus->replaceFirstLetterOfLastSyllabus("g");
        } else if (in_array($diftong, ["lk", "rk"]) && $lastSyllabus == "ki") {
            // kylki, sylki
            // arki
            $syllabus->replaceLastSyllabus("je");
        } else if ($diftong == "nn") {
            // kanne
            $syllabus->replaceFirstLetterOfLastSyllabus("t");
        } else if ($diftong == "tt") {
            // raglette
            $syllabus->removeLastLetterFromSecondToLastSyllabus();
            $syllabus->doubleVowel = false;
        } else if ($diftong == "ll") {
            // nalle
            $syllabus->doubleVowel = false;

        //--------------- second the last syllabuses
        } else if ($secToLastSyllabus == "ai" && $lastSyllabus == "ka") {
            // aika
            $syllabus->removeLastLetterFromSecondToLastSyllabus();
            $syllabus->replaceLastSyllabus("ja");
            $syllabus->doubleVowel = false;
        } else if (preg_match('/(u|y)(ku|ky)$/', $word)) {
            // puku, suku, kyky
            $syllabus->replaceFirstLetterOfLastSyllabus("v");
        } else if ($lastSyllabus == "ki" && in_array($lastInSecLast, ["i", "e"])) {
            // piki, hiki, reki
            $syllabus->replaceLastSyllabus("e");
        } else if ($lastSyllabus == "ke" && in_array($lastInSecLast, ["a", "i", "l"])) {
            // lomake, leike
            $syllabus->replaceFirstLetterOfLastSyllabus("kk");
        } else if ($lastSyllabus == "te" && in_array($lastInSecLast, ["i", "e", "l", "o", "u", "y", "a"])) {
            // liete, polte, ote, näyte, palaute
            $syllabus->replaceFirstLetterOfLastSyllabus("tt");

        //--------------- third the last syllabus first letters
        } else if ($firstLetterInLast == "l" &&
            in_array($lastInSecLast, ["a"])) {
            // roikale
            $syllabus->doubleVowel = true;
        } else if ($firstLetterInLast == "l" &&
            in_array($lastInSecLast, array_merge(Syllabus::WOVELS))) {
            // joule
            $syllabus->doubleVowel = false;
        } else if ($firstLetterInLast == "d") {
            // säde
            $syllabus->replaceFirstLetterOfLastSyllabus("t");
        } else if ($firstLetterInLast == "v" &&
            in_array($lastInSecLast, array_merge(Syllabus::WOVELS, ["l", "r", "a", "i"]))
            && $word !== "terve") {
            // viive, tarve
            $syllabus->replaceFirstLetterOfLastSyllabus("p");
        } else if ($firstLetterInLast == "p" && in_array($lastInSecLast, ["a", "u", "o", "l"])) {
            // käpy, lupa, suopa, kylpy
            $syllabus->replaceFirstLetterOfLastSyllabus("v");
        } else if ($firstLetterInLast == "p" &&
            in_array($lastInSecLast, ["i"])) {
            // ripe
            $syllabus->replaceFirstLetterOfLastSyllabus("pp");
        } else if ($firstLetterInLast == "t" && in_array($lastInSecLast, ["a", "y", "u", "i", "h", "o"])) {
            // pato, lato, mötö, pöytä, pihti, kota
            $syllabus->replaceFirstLetterOfLastSyllabus("d");
        } else if ($lastSyllabus == "e" &&
            in_array($lastInSecLast, ["a", "o", "i"])) {
            // jae, rae
            $syllabus->replaceFirstLetterOfLastSyllabus("ke");
        }
        return $syllabus;
    }
}
