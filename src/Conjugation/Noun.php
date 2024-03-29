<?php

namespace Conjugation;

/**
* class to handle all
*/
class Noun
{
  const WORD_FILE = "words.php";
  const CACHE_FILE = "words.inc";
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

  public array $pronouns = [
        "minä" => ["", "minu", "minu", "minu", "minu", "minu"],
        "sinä" => ["", "sinu", "sinu", "sinu", "sinu", "sinu"],
        "hän" => ["", "häne", "häne", "hänee", "hänt", "häne"],
        "me" => ["", "meidä", "mei", "meidä", "meit", "mei"],
        "te" => ["", "teidä", "tei", "teidä", "teit", "tei"],
        "he" => ["", "heidä", "hei", "heidä", "heit", "hei"],
      ];

  /**
  * constructor
  * @param boolean $cache - use cache or not
  * @return void
  */
  public function __construct(bool $cache = false) {
  $words = [];
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
  public function makeWords() {
    // build the plurals and helper array here
    $singles = [];
    $plurals = [];
    
    foreach ($this->words as $word => $conjugation) {
//      $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
//      $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
//      $conjugation["si"][0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation["si"][0]);
      // form a plural
      $plural = $this->strRreplace($conjugation[0], $conjugation["si"][0], $word)."t";
      $plural_postfix = $conjugation["si"][0]."t";
      
      // revert the word and set it into the helper
      $drow = $this->utf8Strrev($word);
      $c_temp = array_merge([$conjugation[0]], $conjugation["si"]);
      $this->indexed_words[substr($drow, 0, 1)][$drow] = $c_temp;
      $singles[$word] = $c_temp;
      
      if (count($conjugation["pl"]) > 0) {
          // add plural also to helper array
          $larulp = $this->utf8Strrev($plural);

          $p_temp = array_merge([$plural_postfix], $conjugation["pl"]);
          $this->indexed_words[substr($larulp, 0, 1)][$larulp] = $p_temp;
          // add the plural to plurals
          $plurals[$plural] = $p_temp;
      }
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
      $subject = mb_substr($subject, 0, $pos).$replace;
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
      $numeral = preg_replace('/'.$numb.'/', '', $numeral);
      // also make the result ready if the word is numeral
      $result = preg_replace('/'.$numb.'/', ''.$conj.$ender.'', $result);
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
     * checks if the word is pronoun and conjugates that
     * @param string $word to check
     * @param string $ender what to place at the end n|ksi|lle ...
     * @return ?string null if not a numeral else the conjugated numeral
     */
    private function isPronoun(string $word, string $ender, int $use_index): ?string
    {
        /*
        * genetiivi : huo va n ["si"][0] minun, sinun, häne, meidä, teidä, heidä
        * translatiivi : huo va ksi ["si"][1], minuksi, sinuksi, häneksi, meiksi
        * inessiivi : huo va ssa ["si"][1]
        * elatiivi : huo va sta ["si"][1]
        * adessiivi : huo va lla ["si"][1]
        * ablatiivi : huo va lta ["si"][1]
        * allatiivi : huo va lle ["si"][1]
        * abessiivi : huo va tta ["si"][1] minu tta, mei ttä
        * illatiivi : huo paa n ["si"][2] minuu n, mei hin
        * partitive : huo pa a ["si"][3], min ua, mei tä
        * essive : huo pa na ["si"][4], minu na, mei nö
        */
        $pronoun = trim($word);
        $result = trim($word);
        foreach ($this->pronouns as $pro => $conj) {
            if ($pronoun == $pro) {
                if (in_array($pro, ['minä', 'sinä'])) {
                    $this->backVowelWord = false;
                    $ender = str_replace('ä', 'a', $ender);
                }
                // also make the result ready if the word is pronoun
                $result = $conj[$use_index].$ender;
                return $result;
            }

        }
        return null;
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
    $answer = $this->strRreplace($this->conjugation[0], $this->conjugation[1], $word)."t";
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
    } else if ($backVowelPos >= $frontVowelPos) { // there is a, o or u and no ä, ö or y
      $this->backVowelWord = true;
    } else if ($backVowelPos <= $frontVowelPos) { // there is  ä, ö or y and no a, o or u
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
    // then check for pronoun
    $pronoun = $this->isPronoun($word, $ender, $use_index);
    if ($pronoun !== null) {
      $this->word = $word;
      return array("match" => "pronoun", "answer" => $pronoun);
    }

    // if the word is same as previously, use the existing conjugation and bestMatch
    // otherwise match the word again
    if ($word != $this->word) {
      $this->matchWord($word);
      $this->word = $word;
    }
    
    // then we have the bast match, just conjugate and exit
    if (isset($this->conjugation[0]) && !empty($this->conjugation[0])
        && isset($this->conjugation[$use_index])) {
      if (!empty($this->original)) {
        $word = $this->original;
      }
      $word = $this->strRreplace($this->conjugation[0], $this->conjugation[$use_index], $word).$ender;
    } else {
      if (!empty($this->original)) {
        $word = $this->original;
      }
      $word = $word.$ender;
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
      if (isset($this->words[$word][0]) && !empty($this->words[$word][0])
          && isset($this->words[$word][$use_index])) {
        $answer = $this->strRreplace($this->words[$word][0], $this->words[$word][$use_index], $word).$ender;
      } else if (isset($this->words[$word][$use_index]) && !empty($this->words[$word][$use_index])) {
        $answer = $word.$this->words[$word][$use_index].$ender;
      } else {
        $answer = $word.$ender;
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
      } else if ($apos !== false) {
        $a = $apos;
      } else if ($opos !== false) {
        $a = $opos;
      }
      if ($aumlpos !== false && $oumlpos !== false && max($aumlpos, $oumlpos) > $a) {
        $auml = true;
      } else if ($aumlpos !== false && $aumlpos > $a) {
        $auml = true;
      } else if ($oumlpos !== false && $oumlpos > $a) {
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
    } else if (!$auml) {
      foreach ($this->conjugation as $k => $v) {
        $this->conjugation[$k] = str_replace(array("ä", "ö"), array("a", "o"), $this->conjugation[$k]);
      }
    }
  }
}
