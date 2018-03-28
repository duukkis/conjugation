<?php


/**
* conjugate verbs
*/
class Verbicate
{

  // some helpers if we want to conjugate same word (performance)
  private $word = null;
  public $sylls = null;
  public $orig = null;
  public $nbr_of_sylls = null;
  
  public $last_syllabus = null;
  private $backVowelWord = null;

  private $wovels = array("a", "e", "i", "o", "u", "y");
  /**
  * constructor
  * @return void
  */
  public function __construct($word = null) {
    $this->plop($word);
  }

  private function plop($word){
    $this->isBackWovelWord($word);
    $this->word = $word;
    $this->syllabs($word);
    return str_replace(array("ä", "ö", "å"), array("a", "o", "a"), $word);
  }
  
  /**
  * change consonants
  */
  private function consonantGradation($syllabs, $nos) {
    if($nos >= 3){
      $thirdlast = mb_substr($syllabs[$nos-3], -1);
    } else {
      $thirdlast = "";
    }
    $secondfirst = mb_substr($syllabs[$nos-2], 0, 1);
    $secondlast = mb_substr($syllabs[$nos-2], -1);
    
    if($thirdlast == "l" && $secondfirst == "p"){ // kylpeä
      $syllabs[$nos-2] = "v".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "m" && $secondfirst == "p"){ // empiä, ampua
      $syllabs[$nos-2] = "m".mb_substr($syllabs[$nos-2],1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "d"){ // aidata
      $syllabs[$nos-2] = "t".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "h" && $secondfirst == "t"){ // ahnehtia, lähteä
      $syllabs[$nos-2] = "d".mb_substr($syllabs[$nos-2],1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "t"){ // potea, kutea, päteä
      $syllabs[$nos-2] = "d".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "n" && $secondfirst == "t"){ // jakaantua
      $syllabs[$nos-2] = "n".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "r" && $secondfirst == "t"){ // kertoa 
      $syllabs[$nos-2] = "r".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "l" && $secondfirst == "t"){ // paleltua 
      $syllabs[$nos-2] = "l".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "t" && $secondfirst == "t"){ // asettua
      $syllabs[$nos-2] = mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "s" && $secondfirst == "t"){ // arvopaperistua, poistua
      // stays the same

    } else if ($thirdlast == "n" && $secondfirst == "k"){ // henkiä, penkoa
      $syllabs[$nos-2] = "g".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "l" && $secondfirst == "k"){ // hylkiä
      $syllabs[$nos-2] = "j".mb_substr($syllabs[$nos-2],1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "k"){ // hakea, kokea
      $syllabs[$nos-2] = mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "k" && $secondfirst == "k"){ // hankkia
      $syllabs[$nos-2] = mb_substr($syllabs[$nos-2],1);

    } else if ($thirdlast == "r" && $secondfirst == "j"){ // herjetä
      $syllabs[$nos-2] = "k".mb_substr($syllabs[$nos-2],1);

    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "v"){ // kaveta
      $syllabs[$nos-2] = "p".mb_substr($syllabs[$nos-2],1);
    } else if ($thirdlast == "l" && $secondfirst == "v"){ // kelvata
      $syllabs[$nos-2] = "p".mb_substr($syllabs[$nos-2],1);

    } else if ($thirdlast == "p" && $secondfirst == "p"){ // harppoa, oppia
      $syllabs[$nos-2] = mb_substr($syllabs[$nos-2],1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "p"){ // hiipiä, kaapia, ruopia, ...
      $syllabs[$nos-2] = "v".mb_substr($syllabs[$nos-2],1);
    } else if ($this->sylls[$nos-2] == "tu"){ // kaatua
      $syllabs[$nos-2] = "du";
    } else if ($this->sylls[$nos-2] == "ty"){ // jäätyä
      $syllabs[$nos-2] = "dy";
    }
    return $syllabs;
  }
  
  
  public function preesensMe($word) {
    $word = $this->plop($word);
    $w = $this->orig;
    $nos = $this->nbr_of_sylls;
    // aak-kos-taa
    // $secondlast = aakko(s)taa
    // $secondfirst = aak(k)ostaa
    // thirdlast = aa(k)kostaa
    if($nos >= 3){
      $thirdlast = mb_substr($this->sylls[$nos-3], -1);
    } else {
      $thirdlast = "";
    }
    $secondfirst = mb_substr($this->sylls[$nos-2], 0, 1);
    $secondlast = mb_substr($this->sylls[$nos-2], -1);
    
    if($this->backVowelWord){
      $a = "a";
    } else {
      $a = "ä";
    }
    switch($this->last_syllabus){
      case "taa": // tää
        if($secondlast == "t"){ // laittaa
          $w[$nos-1] = $a."n";
        } else if($secondlast == "h" || in_array($secondlast, $this->wovels)){ // johtaa
          $w[$nos-1] = "d".$a."n";
        } else if($secondlast == "n"){ // juontaa
          $w[$nos-1] = "n".$a."n";
        } else if($secondlast == "r"){ // avartaa
          $w[$nos-1] = "r".$a."n";
        } else if($secondlast == "l"){ // kiiltää
          $w[$nos-1] = "l".$a."n";
        } else {
          $w[$nos-1] = "t".$a."n";
        }
        break;
      case "da": // dä
        if ($secondlast == "h") { // tehdä, nähdä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1);
          $w[$nos-1] = "en";
        } else {
          $w[$nos-1] = "n";
        }
        break;
      case "la": // lä
        if(in_array($thirdlast, $this->wovels) && $w[$nos-2] == "tel"){ // haukotella
          $w[$nos-3] .= "t";
        } else if ($thirdlast == "l" && $secondfirst == "l"){ // takellella
          $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
        } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "k"){ // nakella
          $w[$nos-2] = "k".$w[$nos-2];
        } else if (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos-3]) > 1 && $secondfirst == "p"){ // tapella, exclude epäillä
          $w[$nos-2] = "p".$w[$nos-2];
        } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "t"){ // aatella
          $w[$nos-2] = "t".$w[$nos-2];
        } else if ($thirdlast == "r" && $secondfirst == "r"){ // kierrellä
          $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
        } else if ($thirdlast == "n" && $secondfirst == "n"){ // annella
          $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
        } else if ($thirdlast == "m" && $secondfirst == "m"){ // annella
          $w[$nos-2] = "p".mb_substr($w[$nos-2],1);
        } else if ($thirdlast == "h" && $secondfirst == "d"){ // hypähdellä
          $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
        } else if(in_array($thirdlast, $this->wovels) && $secondfirst == "d"){ // huudella
          $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
        }
        $w[$nos-1] = "en";
        break;
      case "ta": // tä
        // verb match class 72
        if (
            preg_match("/(hap|par|mad)ata/",$word) ||
            // exclude fex. lanata
            preg_match("/(.*)(l|n|p|h|y|s|a|lv|ai|aj|av|am|ed|hk|hm|hv|id|ii|im|ir|iu|lm|lv|oj|or|nk|rj|rm|rk|yk|uj|ur)eta/",$word) ||
            // kyetä, paeta, norjeta, ranketa, edetä
            preg_match("/(.*)(aks|eik|eud|eik)ota/",$word) ||
            // heikota
            preg_match("/(.*)(ir|ll|lo|sa|rk|va)ita/",$word) ||
            // ansaita, harkita, hallita, häiritä, iloita
            preg_match("/(.*)(ai|uo|ou)sta/",$word) ||
            // ehkäistä
            preg_match("/(.*)(arv|arj|eik|iev|imm|iuk|oiv|orj|ouk|urj|umm|ust|uum|val|yhj)eta/",$word) ||
            // himmetä, norjeta, tyhjetä
            preg_match("/(.*)(aks)uta/",$word)
            // paksuta
        ) {
          $w = $this->consonantGradation($w, $nos);
          $w[$nos-1] = "en";
          if ($secondlast == "s" && $nos == 2) { // juosta, nousta
            $w[$nos-2] = mb_substr($w[$nos-2], 0, -1)."k";
            $w[$nos-1] = "sen";
          } else if ($secondlast == "s") { // hotkaista
            $w[$nos-1] = "en";
          } else if ($secondlast == "i") { // ravita, suvaita, valita
            $w[$nos-1] = "tsen";
          } else if ($secondlast == "e") { // vanketa
            $w[$nos-1] = "nen";
          } else {
            $w[$nos-1] = "en";
          }
        } else { // verb class 74
          if ($secondlast == "y" && isset($w[$nos-3]) && mb_strlen($w[$nos-3]) == 2) { // lymytä, rymytä, kärytä
          } else if (1 == 2) { // kuivata
          } else if ($secondlast == "y") { // ryöpytä, röyhytä, löylytä
            $w[$nos-2] .= "t";
          } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // loitota
            $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "n" && $secondfirst == "t") { // kontata
            $w[$nos-2] = "t".$w[$nos-2];
          } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // hakata
            $w[$nos-2] = "k".$w[$nos-2];
          } else if (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos-3]) == 2 && $secondfirst == "l") { // hylätä
            $w[$nos-2] = "lk".mb_substr($w[$nos-2],1);
          } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // hypätä
            $w[$nos-2] = "p".$w[$nos-2];
          } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "v") { // kaivata
            $w[$nos-2] = "p".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "l" && $secondfirst == "v") { // kelvata
            $w[$nos-2] = "p".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "a" && $secondfirst == "r") { // karata (not all vowels since kerätä)
            $w[$nos-2] = "rk".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "n" && $secondfirst == "g") { // hangata
            $w[$nos-2] = "k".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "r" && $secondfirst == "r") { // irrota
            $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
          } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // leudota
            $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "h" && $secondfirst == "d") { // kohdata
            $w[$nos-2] = "t".mb_substr($w[$nos-2],1);
          } else if ($thirdlast == "m" && $secondfirst == "m"){ // kimmota
            $w[$nos-2] = "p".mb_substr($w[$nos-2],1);
          } else if($w[$nos-2] == "koo") { // koota
            $w[$nos-2] = "koko";
          }
          $w[$nos-1] = $a."n";
        }
        break;
      case "a": // ä
        $w = $this->consonantGradation($w, $nos);
        $w[$nos-1] = "n";
        break;
      case "paa": // lappaa, nappaa
        $w[$nos-1] = $a."n";
        break;
      case "na": // mennä
      case "ra": // purra
        $w[$nos-1] = "en";
        break;
      case "kaa": // alkaa, jakaa
        if (in_array($secondlast, array("l", "a", "r"))) { // alkaa, jakaa, purkaa
          $w[$nos-1] = mb_substr($w[$nos-1],1,-1)."n";
        } else { // jatkaa
          $w[$nos-1] = mb_substr($w[$nos-1],0,-1)."n";
        }
        break;
      case "jaa": // ajaa
      case "nee": // tarkenee
      case "laa": // palaa
      default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
        $w[$nos-1] = mb_substr($w[$nos-1],0,-1)."n";
        break;
    }
    
    return $this->buildWord($w);
  }
  
  private function buildWord($w){
    return implode('', $w);
  }
  
  
  /**
  * return array
  */
  private function syllabs($word){
    if(empty($word)){
      return array();
    }
    $this->orig = array();
    $orig = $word;
    
    $cons = array("b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "z");
    $wovels = array("a", "e", "i", "o", "u", "y");
    // diftongit
    $dif = array("yi", "ui", "oi", "ai", "ay", " au", "yo", "oy", "uo", "ou", "ie", "ei", "eu", "iu", "ey", "iy");

    $word = trim($word);
    // utf stuff. a:ksi ja o:ksi
    $word = str_replace(array("å", "ä", "ö", "Å", "Ä", "Ö"), array("a", "a", "o", "a", "a", "o"), $word);
    $loop = mb_strlen($word);
    
    // split the word
    $w = str_split(mb_strtolower($word));
    $w[] = " "; // helpers so we dont get notice's
    $w[] = " ";
    $w[] = " ";
    $w[] = " ";

    // put the word here letters and -'s
    $com_word = array();
    for($i = 0;$i < $loop;){
      $d = 1; // how many digits forward
      if(in_array($w[$i], $cons)){
        if(($i+1) >= $loop){ // if last is kons, remove the possible previous -
          $last = array_pop($com_word);
          if($last != "-"){
            $com_word[] = $last;
          }
        }
        $com_word[] = $w[$i];
      } else if(in_array($w[$i], $wovels)){
        $com_word[] = $w[$i];
        if(in_array($w[$i].$w[$i+1], $dif) || $w[$i] == $w[$i+1] || $w[$i+1] == "i"){ // next diftongi, same vokaali or "i" 
          $com_word[] = $w[$i+1];
          $d = 2;
          if(in_array($w[$i+2], $wovels)){
            $com_word[] = "-";
          } else if(in_array($w[$i+2], $cons) && in_array($w[$i+3], $cons) && in_array($w[$i+4], $cons)){
            $com_word[] = $w[$i+2];
            $com_word[] = $w[$i+3];
            $com_word[] = "-";
            $d = 4;
          } else if(in_array($w[$i+2], $cons) && in_array($w[$i+3], $cons)){
            $com_word[] = $w[$i+2];
            $com_word[] = "-";
            $d = 3;
          } else if(in_array($w[$i+2], $cons)){
            $com_word[] = "-";
            $d = 2;
          }
        } else if(in_array($w[$i+1], $wovels)){
          $com_word[] = "-";
          $d = 1;
        } else {
          if(in_array($w[$i+1], $cons) && in_array($w[$i+2], $cons) && in_array($w[$i+3], $cons)){
            $com_word[] = $w[$i+1];
            $com_word[] = $w[$i+2];
            $com_word[] = "-";
            $d = 3;
          } else if(in_array($w[$i+1], $cons) && in_array($w[$i+2], $cons)){
            $com_word[] = $w[$i+1];
            $com_word[] = "-";
            $d = 2;
          } else if(in_array($w[$i+1], $cons)){
            $com_word[] = "-";
            $d = 1;
          }
        }
      }
      $i = $i + $d;
    }
    // now build the word back together
    $sylls = array();
    $tindex = 0;
    $windex = 0; // word index
    foreach($com_word AS $in => $letter){
      if($letter == "-"){
        $tindex++;
      } else if(isset($sylls[$tindex])){
        $sylls[$tindex] .= $letter;
        $this->orig[$tindex] .= mb_substr($orig, $windex, 1);
        $windex++;
      } else {
        $sylls[$tindex] = $letter;
        $this->orig[$tindex] = mb_substr($orig, $windex, 1);
        $windex++;
      }
    }
    $this->last_syllabus = $sylls[$tindex];
    $this->nbr_of_sylls = $tindex + 1;
    $this->sylls = $sylls;
  }
  
  
  /**
  * check if the word is back wovel word
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
  
}


















// OLD STUFF BELOW .... REMOVE WHEN ABOVE IS DONE


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
