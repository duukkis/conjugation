<?php
// include the words
include("words.php");

/**
* class to handle all
*/
class Conjugate {

  // 
  public $words;
  // use this as helper 
  public $indexed_words = array();

  public $numerals = array(
        "yksi" => "yhden",
        "kaksi" => "kahden",
        "kolme" => "kolmen",
        "neljä" => "neljän",
        "viisi" => "viiden",
        "kuusi" => "kuuden",
        "seitsemän" => "seitsemän",
        "kahdeksan" => "kahdeksan",
        "yhdeksän" => "yhdeksän",
        
        "kymmenen|kymmentä" => "kymmenen",
        "toista" => "toista",

        "sataa|sata" => "sadan",
        "tuhatta|tuhat" => "tuhannen",
        
        "miljoonaa|miljoona" => "miljoonan",
        "miljardia|miljardi" => "miljardin",
        "biljoonaa|biljoona" => "biljoonan",
        "triljoonaa|triljoona" => "triljoonan",
      );
  
  // file where to write the serialized words
  private $cache_file = "words.inc";
  
  /**
  * contructor
  * @param array $words words
  * @param boolean $cache whether to use cache or not
  * @return void
  */
  public function __construct($words, $cache = false) {
    $this->words = $words;
    if ($cache) {
      if (!$this->getCacheWords()) {
        $this->makeWords();
        $this->setCacheWords();
      }
    } else {
      $this->makeWords();
    }
  }

  /**
  * load words from cache
  * @return boolean if the loading was successful
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
  * @return void
  */
  private function setCacheWords() {
    $data = array("words" => $this->words, "indexed_words" => $this->indexed_words);
    @file_put_contents($this->cache_file, serialize($data));
  }
  
  /**
  * clears the cache
  * @return void
  */
  public function clearCacheWords() {
    @unlink($this->cache_file);
  }
  
  /**
  * parse the words, make plurals and place all into indexed_words for matching
  * @return void
  */
  public function makeWords() {
    // build the plurals here
    $plurals = array();
    
    foreach ($this->words AS $word => $conjugation) {
      $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
      // form a plural
      $plural = $this->str_lreplace($conjugation[0], $conjugation[1], $word)."t";
      $plural_word = $conjugation[1]."t";
      
      // revert the word and set it into the helper
      $word = $this->utf8_strrev($word);
      $this->indexed_words[substr($word, 0, 1)][$word] = $conjugation;
      
      // add plural also to helper array
      $conjugation[0] = $plural_word;
      $conjugation[1] = $conjugation[2];
      $plural = $this->utf8_strrev($plural);

      $this->indexed_words[substr($plural, 0, 1)][$plural] = $conjugation;
      // add the plural to plurals
      $plurals[$plural] = $conjugation;
    }
    // finally add merge the originals and plurals
    $this->words = array_merge($this->words, $plurals);
  }
  
  /**
  * flip a string
  * @param string $str - string to reverse
  * @return flipped word
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
  * @param string $word - string to check
  * @return mixed false if not a numeral else the conjugated numeral
  */
  private function is_numeral($word) {
    
    $genitive = trim($word);
    $result = trim($word);
    foreach ($this->numerals AS $numb => $conj) {
      // do a fast checking by replacing stuff 
      $genitive = preg_replace('/'.$numb.'/', '', $genitive);
      // also make the result ready if the word is numeral
      $result = preg_replace('/'.$numb.'/', ''.$conj.'', $result);
    }
    if (empty($genitive)) { // numeral, when all is replaced
      return $result;
    } else {
      return false;
    }
  }

  /**
  * find a genitive of a word, match to words
  * @param string $word to conjugate
  * @return array "match" with the word that was matched and "answer" with the confugation
  */
  public function genitive($word) {
    $original = "";
    // first check for numeral
    $numeral = $this->is_numeral($word);
    if ($numeral !== false) {
      return array("match" => "numeral", "answer" => $numeral);
    }
    // first check full match
    if (isset($this->words[$word])) {
      if (isset($this->words[$word][0]) && isset($this->words[$word][1]) && !empty($this->words[$word][0]) && !empty($this->words[$word][1])) {
        $answer = $this->str_lreplace($this->words[$word][0], $this->words[$word][1], $word)."n";
      } else if (isset($this->words[$word][1]) && !empty($this->words[$word][1])) {
        $answer = $word.$this->words[$word][1]."n";
      } else {
        $answer = $word."n";
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
      foreach ($this->indexed_words[$index] AS $w => $conjugation) {
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
    if (isset($conjugation[0]) && isset($conjugation[1]) && !empty($conjugation[0]) && !empty($conjugation[1])) {
      if (!empty($original)) {
        $word = $original;
        if ($auml) {
          $conjugation[0] = str_replace(array("a", "o"), array("ä", "ö"), $conjugation[0]);
          $conjugation[1] = str_replace(array("a", "o"), array("ä", "ö"), $conjugation[1]);
        } else {
          $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
          $conjugation[1] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[1]);
        }
      } else if (!$auml) {
        $conjugation[0] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[0]);
        $conjugation[1] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[1]);
      }
      $word = $this->str_lreplace($conjugation[0], $conjugation[1], $word)."n";
    } else if (isset($conjugation[0]) && isset($conjugation[1]) && !empty($conjugation[1])) {
      if (!empty($original)) {
        $word = $original;
        if ($auml) {
          $conjugation[1] = str_replace(array("a", "o"), array("ä", "ö"), $conjugation[1]);
        } else {
          $conjugation[1] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[1]);
        }
      } else if (!$auml) {
        $conjugation[1] = str_replace(array("ä", "ö"), array("a", "o"), $conjugation[1]);
      }
      $word .= $conjugation[1]."n";
    } else {
      if (!empty($original)) {
        $word = $original;
      }
      $word = $word."n";
    }
    
    return array("match" => strrev($bestMatch), "answer" => $word);
  }
  
} // end of class

