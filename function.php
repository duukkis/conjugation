<?php

/**
* class to handle all
*/
class Conjugate
{

  // array of words
  public $words;
  // use this as helper
  public $indexed_words = array();

  // toista exception is done on function
  public $numerals = array(
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
      );
  
  private $word_file = "words.php";
  private $cache_file = "words.inc";
  
  public function __construct($cache = false) {
    if ($cache) {
      if (!$this->getCacheWords()) {
        include($this->word_file);
        $this->words = $words;
        $this->makeWords();
        $this->setCacheWords();
      }
    } else {
      include($this->word_file);
      $this->words = $words;
      $this->makeWords();
    }
  }

  /**
  * load words from cache
  */
  private function getCacheWords() {
    $c = @file_get_contents($this->cache_file);
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
  */
  private function setCacheWords() {
    $data = array("words" => $this->words, "indexed_words" => $this->indexed_words);
    @file_put_contents($this->cache_file, serialize($data));
  }
  
  /**
  * parse the words, make plurals and place all into indexed_words
  */
  public function makeWords() {
    // build the plurals and helper array here
    $singles = array();
    $plurals = array();
    
    foreach ($this->words as $word => $conjugation) {
      $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
      $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
      $conjugation["si"][0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation["si"][0]);
      // form a plural
      $plural = $this->str_lreplace($conjugation[0], $conjugation["si"][0], $word)."t";
      $plural_postfix = $conjugation["si"][0]."t";
      
      // revert the word and set it into the helper
      $drow = $this->utf8_strrev($word);
      $c_temp = array_merge(array($conjugation[0]), $conjugation["si"]);
      $this->indexed_words[substr($drow, 0, 1)][$drow] = $c_temp;
      $singles[$word] = $c_temp;
      
      // add plural also to helper array
      $larulp = $this->utf8_strrev($plural);

      $p_temp = array_merge(array($plural_postfix), $conjugation["pl"]);
      $this->indexed_words[substr($larulp, 0, 1)][$larulp] = $p_temp;
      // add the plural to plurals
      $plurals[$plural] = $p_temp;
    }
    // finally add merge the originals and plurals
    $this->words = array_merge($singles, $plurals);
  }
  
  /**
  * flip a string
  */
  private function utf8_strrev($str) {
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
  private function str_lreplace($search, $replace, $subject) {
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
  * @return mixed false if not a numeral else the conjugated numeral
  */
  private function is_numeral($word, $ender = 'n') {
    
    $numeral = trim($word);
    $result = trim($word);
    foreach ($this->numerals as $numb => $conj) {
      // do a fast checking by replacing stuff
      $numeral = preg_replace('/'.$numb.'/', '', $numeral);
      // also make the result ready if the word is numeral
      $result = preg_replace('/'.$numb.'/', ''.$conj.$ender.'', $result);
    }
    // toista exception
    $genitive = preg_replace('/toista/', '', $genitive);
    
    if (empty($numeral)) { // numeral, when all is replaced
      return $result;
    } else {
      return false;
    }
  }
  
  
  /**
  * genitive
  * @param string $word to conjugate
  */
  public function genitive($word) {
    return $this->conjugate_word($word, "n", 1);
  }

  /**
  * akkusative
  */
  public function akkusative($word) {
    return array("match" => "akkusative not implemented yet", "answer" => $word);
  }

  /**
  * partitive
  * -a, -ä, -ta, -tä, -tta, -ttä
  */
  public function partitive($word) {
    return array("match" => "partitive not implemented yet", "answer" => $word);
  }
  
  
  /**
  * essive -na, nä
  * WRONG THINK tauti
  */
  public function essive($word) {
    return array("match" => "essive not implemented yet", "answer" => $word);
  }
  
  /**
  * translative
  * @param string $word to conjugate
  */
  public function translative($word) {
    return $this->conjugate_word($word, "ksi", 2);
  }
  
  /**
  * inessive
  * @param string $word to conjugate
  */
  public function inessive($word) {
    if ($this->isFrontWovelWord($word)) {
      return $this->conjugate_word($word, "ssa", 2);
    } else {
      return $this->conjugate_word($word, "ssä", 2);
    }
  }
  
  /**
  * elative
  * @param string $word to conjugate
  */
  public function elative($word) {
    if ($this->isFrontWovelWord($word)) {
      return $this->conjugate_word($word, "sta", 2);
    } else {
      return $this->conjugate_word($word, "stä", 2);
    }
  }

  /**
  * illative
  * 1) Jäätelö putosi hiekka|an. Koputin ove|en. Tulkaa meille kylä|än.
  * Ota lapsi syli|in. Matkustimme kaupunki|in. Luota minu|un. Salama iski talon pääty|yn.
  * 2) Astronautit lensivät kuu|hunja palasivat takaisin maa|han. Lehmä vajosi suo|hon.
  * Varas pakeni pimeään yö|hön. Tutustuimme kaupungin kirkkoi|hin. Ota yhteyttä mei|hin.
  * 3) Tekisi mieli matkustaa vieraa|seen maahan. Tutustuimme uusiin oppilai|siin
  */
  public function illative($word) {
    return array("match" => "illative not implemented yet", "answer" => $word);
  }
  
  /**
  * adessive -lla, -llä
  * @param string $word to conjugate
  */
  public function adessive($word) {
    if ($this->isFrontWovelWord($word)) {
      return $this->conjugate_word($word, "lla", 2);
    } else {
      return $this->conjugate_word($word, "llä", 2);
    }
  }
  
  /**
  * ablatiivi -lta, -ltä
  * @param string $word to conjugate
  */
  public function ablative($word) {
    if ($this->isFrontWovelWord($word)) {
      return $this->conjugate_word($word, "lta", 2);
    } else {
      return $this->conjugate_word($word, "ltä", 2);
    }
  }
  
  /**
  * allative
  * @param string $word to conjugate
  */
  public function allative($word) {
    return $this->conjugate_word($word, "lle", 2);
  }
  
  /**
  * abessive -lta, -ltä
  * @param string $word to conjugate
  */
  public function abessive($word) {
    if ($this->isFrontWovelWord($word)) {
      return $this->conjugate_word($word, "tta", 2);
    } else {
      return $this->conjugate_word($word, "ttä", 2);
    }
  }

  
  /**
  * check if the word is frontWovel word
  * @param string $word string to check
  */
  private function isFrontWovelWord($word) {
    $apos = mb_strrpos($word, "a");
    $opos = mb_strrpos($word, "o");
    $upos = mb_strrpos($word, "u");
    $frontWovelPos = max($apos, $opos, $upos);
    if ($frontWovelPos !== false) { // there is a, o or u
      // we have to check for back wovels to see if the word is yhdyssana
      $apos = mb_strrpos($word, "ä");
      $opos = mb_strrpos($word, "ö");
      $upos = mb_strrpos($word, "y");
      $backWovelPos = max($apos, $opos, $upos);
      if ($backWovelPos === false) {
        return true;
      } else if ($frontWovelPos > $backWovelPos) { // both present
        return true;
      } else { // both present
        return false;
      }
    } else { // no a, o or u
      return false;
    }
  }

  /**
  * conjugate a word, match to this->words
  * @param string $word to conjugate
  * @param string $ender to add to end of the conjugated word n|ksi|lle|ltä
  * @return array "match" with the word that was matched and "answer" with the confugation
  */
  public function conjugate_word($word, $ender, $use_index) {
    
    $original = "";
    // first check for numeral
    $numeral = $this->is_numeral($word, $ender);
    if ($numeral !== false) {
      return array("match" => "numeral", "answer" => $numeral);
    }
    // first check full match
    if (isset($this->words[$word])) {
      if (isset($this->words[$word][0]) && !empty($this->words[$word][0])
          && isset($this->words[$word][$use_index]) && !empty($this->words[$word][$use_index])) {
        $answer = $this->str_lreplace($this->words[$word][0], $this->words[$word][$use_index], $word).$ender;
      } else if (isset($this->words[$word][$use_index]) && !empty($this->words[$word][$use_index])) {
        $answer = $word.$this->words[$word][$use_index].$ender;
      } else {
        $answer = $word.$ender;
      }
      return array("match" => $word, "answer" => $answer);
    }
    // ------- then check the ao OR äö this is used so that both a and ä conjugate the same
    $auml = false; // is the last one a/o or ä/ö
    $aumlpos = mb_strrpos($word, "ä");
    $oumlpos = mb_strrpos($word, "ö");
    $apos = mb_strrpos($word, "a");
    $opos = mb_strrpos($word, "o");
    if ($aumlpos !== false || $oumlpos !== false) {
      $original = $word;
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
    $drow = $this->utf8_strrev($word);
    
    $index = mb_substr($drow, 0, 1);

    $wordLength = mb_strlen($word);
    
    $bestMatch = "";
    $bestMatchLetters = 0;
    if (isset($this->indexed_words[$index])) {
      foreach ($this->indexed_words[$index] as $w => $conjugation) {
        $match = 0;
        $shorterWord = min(mb_strlen($w), $wordLength);
        for ($i = 0; $i < $shorterWord; $i++) {
          if (mb_substr($w, $i, 1) == mb_substr($drow, $i, 1)) {
            $match++;
          } else {
            $i = 100; // out of for loop
          }
        }
        if ($match > $bestMatchLetters) {
          $bestMatchLetters = $match;
          $bestMatch = $w;
        }
      }
      $conjugation = $this->indexed_words[$index][$bestMatch];
    }
    // then we have the bast match, just make the genitive and exit
    if (isset($conjugation[0]) && !empty($conjugation[0])
        && isset($conjugation[$use_index]) && !empty($conjugation[$use_index])) {
      if (!empty($original)) {
        $word = $original;
        if ($auml) {
          $conjugation[0] = str_replace(array("a", "o"), array("ä", "ö"), $conjugation[0]);
          $conjugation[$use_index] = str_replace(array("a", "o"), array("ä", "ö"), $conjugation[$use_index]);
        } else {
          $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
          $conjugation[$use_index] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[$use_index]);
        }
      } else if (!$auml) {
        $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
        $conjugation[$use_index] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[$use_index]);
      }
      $word = $this->str_lreplace($conjugation[0], $conjugation[$use_index], $word).$ender;
    } else if (isset($conjugation[0]) && isset($conjugation[$use_index]) && !empty($conjugation[$use_index])) {
      if (!empty($original)) {
        $word = $original;
        if ($auml) {
          $conjugation[$use_index] = str_replace(array("a", "o"), array("ä", "ö"), $conjugation[$use_index]);
        } else {
          $conjugation[$use_index] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[$use_index]);
        }
      } else if (!$auml) {
        $conjugation[$use_index] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[$use_index]);
      }
      $word .= $conjugation[$use_index].$ender;
    } else {
      if (!empty($original)) {
        $word = $original;
      }
      $word = $word.$ender;
    }
    
    return array("match" => strrev($bestMatch), "answer" => $word);
  }
} // end of class
