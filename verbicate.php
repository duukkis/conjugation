<?php

/**
* class to handle all
*/
class Verbigate
{

  // some helpers if we want to conjugate same word (performance)
  private $word = null;
  private $index = null;
  private $bestMatch = null;
  private $original = null;
  private $conjugation = null;
  private $auml = null;
  private $backVowelWord = null;
  
  // array of words
  public $words;
  // use this as helper
  public $indexed_words = array();
  
  private $word_file = "verbs.php";
  private $cache_file = "verbs.inc";
  
  /**
  * constructor
  * @param boolean $cache - use cache or not
  * @return void
  */
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
  * @return boolean - if loading was succesful
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
  * parse the words, make plurals and place all into indexed_words
  * @return void
  */
  public function makeWords() {
    // build helper array here
    foreach ($this->words as $word => $conjugation) {
      $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
      $conjugation = str_replace(array("ä", "ö"), array("a", "o"), $conjugation);
      
      // revert the word and set it into the helper
      $drow = $this->utf8Strrev($word);
      $this->indexed_words[substr($drow, 0, 1)][$drow] = $conjugation;
    }
  }
  
  /**
  * flip a string
  * @param string $str - string to reverse
  * @return string gnirts
  */
  private function utf8Strrev($str) {
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
  private function strRreplace($search, $replace, $subject) {
    $pos = mb_strrpos($subject, $search);
    if ($pos !== false) {
      $subject = mb_substr($subject, 0, $pos).$replace;
    }
    return $subject;
  }

  /**
  * 1st present tense
  */
  public function presentTenseMe($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "n", 1);
  }
  
  /**
  * 2nd present tense
  */
  public function presentTenseYou($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "t", 1);
  }
  
  /**
  * 3rd present tense
  */
  public function presentTenseShe($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "", 2);
  }
  
  /**
  * present tense we
  */
  public function presentTenseWe($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "mme", 1);
  }
  
  /**
  * present tense you plural
  */
  public function presentTenseYouP($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "tte", 1);
  }
  
  /**
  * present tense they
  */
  public function presentTenseThey($word){
    if($this->isBackWovelWord($word)) {
      return $this->conjugateWord($word, "vat", 3);
    } else {
      return $this->conjugateWord($word, "vät", 3);
    }
  }
  
  /**
  * 1st imperfect
  */
  public function imperfectMe($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "n", 4);
  }
  
  /**
  * 2nd imperfect
  */
  public function imperfectYou($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "t", 4);
  }
  
  /**
  * 3rd imperfect
  */
  public function imperfectShe($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "", 5);
  }
  
  /**
  * imperfect we
  */
  public function imperfectWe($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "mme", 4);
  }
  
  /**
  * imperfect you plural
  */
  public function imperfectYouP($word){
    $this->isBackWovelWord($word);
    return $this->conjugateWord($word, "tte", 4);
  }
  
  /**
  * imperfect trey
  */
  public function imperfectThey($word){
    if($this->isBackWovelWord($word)) {
      return $this->conjugateWord($word, "vat", 5);
    } else {
      return $this->conjugateWord($word, "vät", 5);
    }
  }
  
  
  /**
  * check if the word is frontWovel word
  * NOTE: HAS TO BE CALLED BEFORE conjugateWord function
  * @param string $word string to check
  * @return boolean is back wovel
  */
  private function isBackWovelWord($word) {
    // if the same word is being checked, use the previous result instead of matching again
    if ($word == $this->word && $this->backVowelWord !== null) {
      return $this->backVowelWord;
    } else {
      // nullify
      $this->backVowelWord = null;
    }
    
    $backVowelPos = -1;
    $apos = mb_strrpos($word, "a");
    if ($apos !== false) {
      $backVowelPos = $apos;
    }
    $opos = mb_strrpos($word, "o");
    if ($opos !== false && $opos > $backVowelPos) {
      $backVowelPos = $opos;
    }
    $upos = mb_strrpos($word, "u");
    if ($upos !== false && $upos > $backVowelPos) {
      $backVowelPos = $upos;
    }
    
    $frontVowelPos = -1;
    $apos = mb_strrpos($word, "ä");
    if ($apos !== false) {
      $frontVowelPos = $apos;
    }
    $opos = mb_strrpos($word, "ö");
    if ($opos !== false && $opos > $frontVowelPos) {
      $frontVowelPos = $opos;
    }
    $upos = mb_strrpos($word, "y");
    if ($upos !== false && $upos > $frontVowelPos) {
      $frontVowelPos = $upos;
    }
    
    if ($frontVowelPos == -1 && $backVowelPos == -1) { // only i's and e's
      $this->backVowelWord = false;
    } else if ($backVowelPos >= 0 && $frontVowelPos == -1) { // there is a, o or u and no ä, ö or y
      $this->backVowelWord = true;
    } else if ($backVowelPos == -1 && $frontVowelPos >= 0) { // there is  ä, ö or y and no a, o or u
      $this->backVowelWord = false;
    } else if ($backVowelPos > $frontVowelPos) { // both present (combined word)
      $this->backVowelWord = true;
    } else if ($backVowelPos < $frontVowelPos){  // both present frontWovel later
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
  public function conjugateWord($word, $ender, $use_index) {
    // full match
    $fMatch = $this->fullMatchCheck($word, $use_index, $ender);
    if ($fMatch !== false) {
      $this->word = $word;
      return $fMatch;
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
  private function fullMatchCheck($word, $use_index, $ender){
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
  * @return string - the matched word against which to conjugate
  */
  private function matchWord($word) {
    // ------- have the original if we have to convert some ä's / a's
    $this->original = null;
    // ------- then check the ao OR äö this is used so that both a and ä conjugate the same
    $this->auml = false; // is the last one ä/ö and not a/o
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
        $this->auml = true;
      } else if ($aumlpos !== false && $aumlpos > $a) {
        $this->auml = true;
      } else if ($oumlpos !== false && $oumlpos > $a) {
        $this->auml = true;
      }
    }
    
    // easier to do this with a's and o's than ä's and ö's
    $word = str_replace(array("ä", "ö"), array("a", "o"), $word);
    $drow = $this->utf8Strrev($word);
    
    $this->index = mb_substr($drow, 0, 1);

    $wordLength = mb_strlen($word);
    $this->bestMatch = "";
    $bestMatchLetters = 0;
    if (isset($this->indexed_words[$this->index])) {
      foreach ($this->indexed_words[$this->index] as $w => $useOnlyKeys) {
        $body_end = $useOnlyKeys[0];
        $match = 0;
        if (mb_substr($word, 0-mb_strlen($body_end)) == $body_end) {
          $shorterWord = min(mb_strlen($w), $wordLength);
          for ($i = 0; $i < $shorterWord; $i++) {
            if (mb_substr($w, $i, 1) == mb_substr($drow, $i, 1)) {
              $match++;
            } else {
              $i = 1000; // out of for loop
            }
          }
          if ($match > $bestMatchLetters) {
            $bestMatchLetters = $match;
            $this->bestMatch = $w;
          }
        }
      }
      $this->conjugation = $this->indexed_words[$this->index][$this->bestMatch];
      $this->bestMatch = strrev($this->bestMatch);
    }
    
    // fix the conjugation with the umlauts
    if (!empty($this->original) && !empty($this->conjugation)) {
      if ($this->auml) {
        // fix all conjugations
        foreach ($this->conjugation as $k => $v) {
          $this->conjugation[$k] = str_replace(array("a", "o"), array("ä", "ö"), $this->conjugation[$k]);
        }
      } else {
        foreach ($this->conjugation as $k => $v) {
          $this->conjugation[$k] = str_replace(array("ä", "ö"), array("a", "o"), $this->conjugation[$k]);
        }
      }
    } else if (!$this->auml && !empty($this->conjugation)) {
      foreach ($this->conjugation as $k => $v) {
        $this->conjugation[$k] = str_replace(array("ä", "ö"), array("a", "o"), $this->conjugation[$k]);
      }
    }
    
  }
}
