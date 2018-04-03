<?php


/**
* conjugate verbs
*/
class Verbicate
{

  // word we are conjugating
  private $word = null;
  // syllabuses with ä replaced with a
  private $sylls = null;
  // syllabuses with nothing replaced
  private $orig = null;
  // number of syllabuses
  private $nbr_of_sylls = null;
  
  // last syllabus
  private $last_syllabus = null;
  // is the word bach vowel word, if false then front wovel word
  private $backVowelWord = null;
  // ender we use minä haistan > n
  private $ender = "";

  private $wovels = array("a", "e", "i", "o", "u", "y", "ä", "ö");
  /**
  * constructor
  * @return void
  */
  public function __construct($word = null) {
    $this->init($word);
  }

  private function init($word){
    $this->isBackWovelWord($word);
    $this->word = $word;
    $this->syllabs($word);
    return str_replace(array("ä", "ö", "å"), array("a", "o", "a"), $word);
  }
  
  public function preesensMe($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $a = "a"; } else { $a = "ä"; }
    $this->ender = "n";
    return $this->conjugatePreesensWithGradation($word, $a, "e");
  }
  
  public function preesensYou($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $a = "a"; } else { $a = "ä"; }
    $this->ender = "t";
    return $this->conjugatePreesensWithGradation($word, $a, "e");
  }
  
  public function preesensSHe($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $a = "aa"; } else { $a = "ää"; }
    $this->ender = "";
    return $this->conjugatePreesensWithoutGradation($word, $a, "ee");
  }
  
  public function preesensPluralWe($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $a = "a"; } else { $a = "ä"; }
    $this->ender = "mme";
    return $this->conjugatePreesensWithGradation($word, $a, "e");
  }

  public function preesensPluralYou($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $a = "a"; } else { $a = "ä"; }
    $this->ender = "tte";
    return $this->conjugatePreesensWithGradation($word, $a, "e");
  }

  public function preesensPluralThey($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $a = "a"; } else { $a = "ä"; }
    $this->ender = "v".$a."t";
    return $this->conjugatePreesensWithoutGradation($word, $a, "e");
  }

  public function imperfectMe($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $o = "o"; } else { $o = "ö"; }
    $this->ender = "n";
    return $this->conjugateImperfectWithGradation($word, $o, "i");
  }

  public function imperfectYou($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $o = "o"; } else { $o = "ö"; }
    $this->ender = "t";
    return $this->conjugateImperfectWithGradation($word, $o, "i");
  }

  public function imperfectSHe($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $o = "o"; } else { $o = "ö"; }
    $this->ender = "";
    return $this->conjugateImperfectWithoutGradation($word, $o, "i");
  }

  public function imperfectPluralWe($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $o = "o"; } else { $o = "ö"; }
    $this->ender = "mme";
    return $this->conjugateImperfectWithGradation($word, $o, "i");
  }

  public function imperfectPluralYou($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $o = "o"; } else { $o = "ö"; }
    $this->ender = "tte";
    return $this->conjugateImperfectWithGradation($word, $o, "i");
  }

  public function imperfectPluralThey($word) {
    $word = $this->init($word);
    if ($this->backVowelWord){ $o = "o"; $this->ender = "vat"; } else { $o = "ö"; $this->ender = "vät"; }
    return $this->conjugateImperfectWithoutGradation($word, $o, "i");
  }

  /**
  * PREESENS
  * me, you, we, you
  */
  private function conjugatePreesensWithGradation($word, $a, $e) {
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

    switch($this->last_syllabus){
      case "taa": // tää
        $w = $this->taaVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast, $a);
        $w[$nos-1] .= $this->ender;
        break;
      case "da": // dä
        if ($secondlast == "h") { // tehdä, nähdä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1);
          $w[$nos-1] = $e.$this->ender;
        } else {
          $w[$nos-1] = $this->ender;
        }
        break;
      case "la": // lä
        $w = $this->laVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
        $w[$nos-1] = $e.$this->ender;
        break;
      case "ta": // tä
        // verb match class 72
        if ($this->isVerbClass72($word)) {
          $w[$nos-1] = "";
          $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          
          // if last is wovel then nen, else en
          if(in_array(mb_substr($w[$nos-2],-1), $this->wovels)) {
            $w[$nos-1] = "n".$e.$this->ender;
          } else {
            $w[$nos-1] = $e.$this->ender;
          }
        } else { // verb class 74
          $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          $w[$nos-1] = $a.$this->ender;
        }
        break;
      case "a": // ä
        $w = $this->aVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
        $w[$nos-1] = $this->ender;
        break;
      case "paa": // lappaa, nappaa
        $w[$nos-1] = $a.$this->ender;
        break;
      case "na": // mennä
      case "ra": // purra
        $w[$nos-1] = $e.$this->ender;
        break;
      case "kaa": // alkaa, jakaa
        if (in_array($secondlast, array("l", "a", "r"))) { // alkaa, jakaa, purkaa
          $w[$nos-1] = mb_substr($w[$nos-1],1,-1).$this->ender;
        } else { // jatkaa
          $w[$nos-1] = mb_substr($w[$nos-1],0,-1).$this->ender;
        }
        break;
      case "jaa": // ajaa
      case "nee": // tarkenee
      case "laa": // palaa
      default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
        $w[$nos-1] = mb_substr($w[$nos-1],0,-1).$this->ender;
        break;
    }
    
    return $this->buildWord($w);
  }
  
  /**
  * PREESENS
  * he, she, they
  */
  private function conjugatePreesensWithoutGradation($word, $a, $e) {
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

    switch($this->last_syllabus){
      case "taa": // tää
        $w[$nos-1] = "t".$a.$this->ender;
        break;
      case "da": // dä
        if ($secondlast == "h") { // tehdä, nähdä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1);
          $w[$nos-1] = "k".$e.$this->ender;
        } else {
          $w[$nos-1] = $this->ender;
        }
        break;
      case "la": // lä
        $w = $this->laVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
        $w[$nos-1] = $e.$this->ender;
        break;
      case "ta": // tä
        // verb match class 72
        if ($this->isVerbClass72($word)) {
          $w[$nos-1] = "";
          $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          // if last is wovel then nen, else en
          if(in_array(mb_substr($w[$nos-2],-1), $this->wovels)) {
            $w[$nos-1] = "n".$e.$this->ender;
          } else {
            $w[$nos-1] = $e.$this->ender;
          }
        } else { // verb class 74
          $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          if($secondlast == "a" || $secondlast == "ä" && mb_strlen($a) == 2){
            $a = mb_substr($a, 0, 1);
          }
          $w[$nos-1] = $a.$this->ender;
        }
        break;
      case "a": // ä
        if (!empty($this->ender)){
          $w[$nos-1] = mb_substr($w[$nos-1],0,-1).$this->ender;
        } else { // rest jäätyä > jäätyy, kaatua > kaatua
          $w[$nos-1] = mb_substr($w[$nos-2],-1);
        }
        break;
      case "paa": // lappaa, nappaa
        $w[$nos-1] = $a.$this->ender;
        break;
      case "na": // mennä
      case "ra": // purra
        $w[$nos-1] = $e.$this->ender;
        break;
      case "kaa": // alkaa, jakaa
        break;
      case "jaa": // ajaa
      case "nee": // tarkenee
      case "laa": // palaa
      default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
        if(!empty($this->ender)){
          $w[$nos-1] = mb_substr($w[$nos-1],0,-1).$this->ender;
        }
        break;
    }
    return $this->buildWord($w);
  }
  
  /**
  * IMPERFECT
  * me, you, we, you
  */
  private function conjugateImperfectWithGradation($word, $o, $i) {
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
    $secondlasttwo = mb_substr($this->sylls[$nos-2], -2);

    switch($this->last_syllabus){
      case "taa": // tää
        if($secondlast == "t" && $thirdlast == "t"){ // laittaa
          $w[$nos-1] = $i;
        } else if($w[$nos-2] == "tie"){ // tietää
          $w[$nos-1] = "s".$i;
        } else if($secondlast == "h" || in_array($secondlast, $this->wovels)){ // johtaa
          $w[$nos-1] = "d".$i;
        } else if(in_array($w[$nos-2], array("an"))){
          // antaa
          $w[$nos-1] = "n".$o.$i;
        } else if(in_array($w[$nos-2], array("aut", "lait", "saat", "kat"))){
          // auttaa
          $w[$nos-1] = $o.$i;
        } else if($secondlast == "n"){ // juontaa
          $w[$nos-1] = "s".$i;
        } else if($secondlast == "r"){ // avartaa
          $w[$nos-1] = "s".$i;
        } else if($secondlast == "s"){ // vastustaa
          $w[$nos-1] = "t".$i;
        } else if($secondlast == "l"){ // kiiltää
          $w[$nos-1] = "s".$i;
        } else {
          $w[$nos-1] = $i;
        }
        $w[$nos-1] .= $this->ender;
        break;
      case "da": // dä
        if ($secondlast == "h") { // tehdä, nähdä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1).$i;
        } else if ($secondlasttwo == "ay") { // käydä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1)."v".$i;
        } else if ($secondlasttwo == "uo") { // juoda
          $w[$nos-2] = mb_substr($w[$nos-2],0,1).$o.$i;
        } else if ($secondlasttwo == "yo") { // lyödä
          $w[$nos-2] = mb_substr($w[$nos-2],0,1).$o.$i;
        } else if ($secondlasttwo == "ie") { // viedä
          $w[$nos-2] = mb_substr($w[$nos-2],0,1)."e".$i;
        } else if (in_array($secondlasttwo, array("aa", "ää", "oo", "uu", "yy"))) { // saada, myydä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1).$i;
        } else if ($secondlast != $i){
          $w[$nos-1] .= $i;
        }
        $w[$nos-1] = $this->ender;
        break;
      case "la": // lä
        $w = $this->laVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
        $w[$nos-1] = $i.$this->ender;
        break;
      case "ta": // tä
        // TODO:
        // verb match class 72
        if ($this->isVerbClass72($word)) {
          $w[$nos-1] = "";
          $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          // if last is wovel then nen, else en
          if(in_array(mb_substr($w[$nos-2],-1), $this->wovels)) {
            $w[$nos-1] = "n".$i.$this->ender;
          } else {
            $w[$nos-1] = $i.$this->ender;
          }
        } else { // verb class 74
          $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          $w[$nos-1] = "s".$i.$this->ender;
        }
        break;
      case "a": // ä
        $w = $this->aVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
        if (mb_substr($w[$nos-2],-1) == "e") {
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1); // remove e
          $w[$nos-1] = $i.$this->ender;
        } else if (mb_substr($w[$nos-2],-1) != $i) {
          $w[$nos-1] = $i.$this->ender;
        } else {
          $w[$nos-1] = $this->ender;
        }
        break;
      case "paa": // lappaa, nappaa
        $w[$nos-1] = mb_substr($w[$nos-1],0,2)."s".$i.$this->ender;
        break;
      case "na": // mennä
      case "ra": // purra
        $w[$nos-1] = $i.$this->ender;
        break;
      case "kaa": // alkaa, jakaa
        if (in_array($secondlast, array("l", "a"))) { // alkaa, jakaa
          $w[$nos-1] = $o.$i.$this->ender;
        } else if(in_array($secondlast, array("r"))){ // purkaa
          $w[$nos-1] = $i.$this->ender;
        } else { // jatkaa
          $w[$nos-1] = mb_substr($w[$nos-1],0,1).$o.$i.$this->ender;
        }
        break;
      case "laa": 
        if ($w[$nos-2] == "e") { // elää
          $w[$nos-1] = mb_substr($w[$nos-1],0,1).$i.$this->ender;
        } else { // palaa etc
          $w[$nos-1] = mb_substr($w[$nos-1],0,1).$o.$i.$this->ender;
        }
        break;
      case "nee": // tarkenee
        $w[$nos-1] = mb_substr($w[$nos-1],0,1).$i.$this->ender;
      case "jaa": // ajaa
      default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
        $w[$nos-1] = mb_substr($w[$nos-1],0,1).$o.$i.$this->ender;
        break;
    }
    
    return $this->buildWord($w);
  }
  
  /**
  * IMPERFECT
  * he, they
  */
  private function conjugateImperfectWithoutGradation($word, $o, $i) {
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
    $secondlasttwo = mb_substr($this->sylls[$nos-2], -2);

    switch($this->last_syllabus){
      case "taa": // tää
        if(in_array($this->sylls[$nos-2], array("an", "ut", "lait", "saat", "kat"))){
          $w[$nos-1] = "t".$o.$i.$this->ender;
        } else if (in_array($secondlast, array("r", "n", "l"))) {
          $w[$nos-1] = "s".$i.$this->ender;
        } else {
          $w[$nos-1] = "t".$i.$this->ender;
        }
        break;
      case "da": // dä
        $w[$nos-1] = $this->ender;

        if ($secondlast == "h") { // tehdä, nähdä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1);
          $w[$nos-1] = "k".$i.$this->ender;
        
        } else if ($secondlasttwo == "ay") { // käydä
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1)."v".$i;
        } else if ($secondlasttwo == "uo") { // juoda
          $w[$nos-2] = mb_substr($w[$nos-2],0,1).$o.$i;
        } else if ($secondlasttwo == "yo") { // lyödä
          $w[$nos-2] = mb_substr($w[$nos-2],0,1).$o.$i;
        } else if ($secondlasttwo == "ie") { // viedä
          $w[$nos-2] = mb_substr($w[$nos-2],0,1)."e".$i;
        } else if(in_array($secondlasttwo, array("aa", "ää", "oo", "uu", "yy"))) {
          $w[$nos-2] = mb_substr($w[$nos-2], 0, -1);
          $w[$nos-1] = $i.$this->ender;
        } else if ($secondlast != $i) {
          $w[$nos-1] = $i.$this->ender;
        }
        break;
      case "la": // lä
        $w = $this->laVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
        $w[$nos-1] = $i.$this->ender;
        break;
      case "ta": // tä
        // verb match class 72
        if ($this->isVerbClass72($word)) {
          $w[$nos-1] = "";
          $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          // if last is wovel then nen, else en
          if(in_array(mb_substr($w[$nos-2],-1), $this->wovels)) {
            $w[$nos-1] = "n".$i.$this->ender;
          } else {
            $w[$nos-1] = $i.$this->ender;
          }
        } else { // verb class 74
          $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
          $w[$nos-1] = "s".$i.$this->ender;
        }
        break;
      case "a": // ä
        if ($secondlast == "e"){
          $w[$nos-2] = mb_substr($w[$nos-2],0,-1);
        }
        $w[$nos-1] = "";
        if(mb_substr($w[$nos-2],-1) != $i){
          $w[$nos-1] = $i;
        }
        $w[$nos-1] .= $this->ender;
        break;
      case "paa": // lappaa, nappaa
        $w[$nos-1] = mb_substr($w[$nos-1],0,-1)."s".$i.$this->ender;
        break;
      case "na": // mennä
      case "ra": // purra
        $w[$nos-1] = $i.$this->ender;
        break;
      case "kaa": // alkaa, jakaa
      case "jaa": // ajaa
      case "nee": // tarkenee
      case "laa": // palaa
      default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
        if ($w[$nos-2] == "e"){
          $w[$nos-1] = mb_substr($w[$nos-1],0,1).$i.$this->ender;
        } else {
          $w[$nos-1] = mb_substr($w[$nos-1],0,1).$o.$i.$this->ender;
        }
        break;
    }
    
    return $this->buildWord($w);
  }
  
  /**
  * build the final word we can return
  */
  private function buildWord($w){
    return implode('', $w);
  }
  
  
  private function taaVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast, $a) {
    if($secondlast == "t"){ // laittaa
      $w[$nos-1] = $a;
    } else if($secondlast == "h" || in_array($secondlast, $this->wovels)){ // johtaa
      $w[$nos-1] = "d".$a;
    } else if($secondlast == "n"){ // juontaa
      $w[$nos-1] = "n".$a;
    } else if($secondlast == "r"){ // avartaa
      $w[$nos-1] = "r".$a;
    } else if($secondlast == "l"){ // kiiltää
      $w[$nos-1] = "l".$a;
    } else {
      $w[$nos-1] = "t".$a;
    }
    return $w;
  }
  /**
  * conjugation for ta-verbs 72
  */
  private function taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast) {
    if ($word == "juosta") { // juosta
      $w[$nos-2] = mb_substr($w[$nos-2], 0, -1)."ks";
    } else if ($secondfirst == "d") { // pidetä
      $w[$nos-2] = "t".mb_substr($w[$nos-2],1)."n";
    } else if ($secondlast == "s") { // hotkaista, nousta

    } else if ($secondlast == "i") { // ravita, suvaita, valita
      $w[$nos-2] .= "ts";
    } else if ($thirdlast == "r" && $secondfirst == "j") { // tarjeta
      $w[$nos-2] = "k".mb_substr($w[$nos-2],1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "v") { // kaveta
      $w[$nos-2] = "p".mb_substr($w[$nos-2],1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // suipeta
      $w[$nos-2] = "p".$w[$nos-2];
    } else if (in_array($w[$nos-2], array("ke", "e"))) { // vanketa, paeta
      $w[$nos-2] = "k".$w[$nos-2];
    }
    return $w;
  }
  
  /**
  * conjugation for ta-verbs 74
  */
  private function taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast){
    if ($secondlast == "y" && isset($w[$nos-3]) && mb_strlen($w[$nos-3]) == 2) { // lymytä, rymytä, kärytä
      // nothing, check if necessary
    } else if ($secondlast == "y") { // ryöpytä, röyhytä, löylytä
      $w[$nos-2] .= "t";
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // loitota, mitata
      $w[$nos-2] = "t".$w[$nos-2];
    } else if ($thirdlast == "n" && $secondfirst == "t") { // kontata
      $w[$nos-2] = "t".$w[$nos-2];
    } else if ($thirdlast == "n" && $secondfirst == "n") { // rynnata
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // hakata
      $w[$nos-2] = "k".$w[$nos-2];
    } else if ($thirdlast == "r" && $secondfirst == "k") { // virkata
      $w[$nos-2] = "k".$w[$nos-2];
    } else if (in_array($word, array("varata", "kelata"))) {

    } else if (in_array($thirdlast, array("y", "e")) && mb_strlen($w[$nos-3]) == 2 && $secondfirst == "l") {
      // hylätä, pelätä
      $w[$nos-2] = "lk".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // hypätä
      $w[$nos-2] = "p".$w[$nos-2];
    } else if (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos-3]) == 2 && $w[$nos-2] == "vi") { // hävitä
      // nothing
    } else if (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos-3]) == 2 && $w[$nos-2] == "ve") { // ruveta
      $w[$nos-2] = "p".mb_substr($w[$nos-2], 1);
    } else if ($secondfirst == "v" &&
       isset($w[$nos-3]) && in_array($w[$nos-3], array("le", "lu", "ta", "kai", "kel", "kii"))) {
      $w[$nos-2] = "p".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // aidata
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "r" && $secondfirst == "j") { // herjetä / tarjota which way to go
      // $w[$nos-2] = "k".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "h" && $secondfirst == "j" && mb_strlen($w[$nos-3]) == 3) { // puhjeta, exclude ohjata
      $w[$nos-2] = "k".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "l" && $secondfirst == "j") { // teljetä
      $w[$nos-2] = "k".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "y" && $secondfirst == "e") { // kyetä
      $w[$nos-2] = "k".$w[$nos-2];
    } else if ($nos >= 3 && mb_strlen($w[$nos-3]) == 1 && $secondfirst == "h") { // uhata
      $w[$nos-2] = "hk".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "a" && mb_strlen($w[$nos-3]) == 2 && $secondfirst == "r") {
      // karata (not all vowels since kerätä), varata also bad
      $w[$nos-2] = "rk".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "n" && $secondfirst == "g") { // hangata
      $w[$nos-2] = "k".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "r" && $secondfirst == "r") { // irrota
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "l" && $secondfirst == "l") { // vallata
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // leudota
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "h" && $secondfirst == "d") { // kohdata
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "m" && $secondfirst == "m") { // kimmota
      $w[$nos-2] = "p".mb_substr($w[$nos-2], 1);
    } else if ($w[$nos-2] == "taa") { // taata
      $w[$nos-2] = "taka";
    } else if ($w[$nos-2] == "maa") { // maata
      $w[$nos-2] = "maka";
    } else if ($w[$nos-2] == "koo") { // koota
      $w[$nos-2] = "koko";
    }
    return $w;
  }
  
  /**
  * conjugation for a-verbs
  */
  private function aVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast) {
    if ($thirdlast == "l" && $secondfirst == "p") { // kylpeä
      $w[$nos-2] = "v".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "m" && $secondfirst == "p") { // empiä, ampua
      $w[$nos-2] = "m".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "h" && $secondfirst == "t") { // ahnehtia, lähteä
      $w[$nos-2] = "d".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // potea, kutea, päteä
      $w[$nos-2] = "d".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "n" && $secondfirst == "t") { // jakaantua
      $w[$nos-2] = "n".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "r" && $secondfirst == "t") { // kertoa
      $w[$nos-2] = "r".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "l" && $secondfirst == "t") { // paleltua
      $w[$nos-2] = "l".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "t" && $secondfirst == "t") { // asettua
      $w[$nos-2] = mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "s" && $secondfirst == "t") { // arvopaperistua, poistua
      // stays the same
    } else if ($thirdlast == "n" && $secondfirst == "k") { // henkiä, penkoa
      $w[$nos-2] = "g".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "l" && $secondfirst == "k") { // hylkiä
      $w[$nos-2] = "j".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "r" && $secondfirst == "k") { // särkeä, pyrkiä
      $w[$nos-2] = mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // hakea, kokea
      $w[$nos-2] = mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "k" && $secondfirst == "k") { // hankkia
      $w[$nos-2] = mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "p" && $secondfirst == "p") { // harppoa, oppia
      $w[$nos-2] = mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // hiipiä, kaapia, ruopia, ...
      $w[$nos-2] = "v".mb_substr($w[$nos-2], 1);
    } else if ($this->sylls[$nos-2] == "tu") { // kaatua
      $w[$nos-2] = "du";
    } else if ($this->sylls[$nos-2] == "ty") { // jäätyä
      $w[$nos-2] = "dy";
    }
    return $w;
  }
  
  /**
  * conjugation for la-verbs
  */
  private function laVerb($word, $w, $nos, $secondfirst, $secondlast, $thirdlast) {
    if (in_array($thirdlast, $this->wovels) && $w[$nos-2] == "tel") { // haukotella
      $w[$nos-3] .= "t";
    } else if ($thirdlast == "l" && $secondfirst == "l") { // takellella
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // nakella
      // nothing
    } else if (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos-3]) > 1 && $secondfirst == "p") { // tapella, exclude epäillä
      $w[$nos-2] = "p".$w[$nos-2];
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // aatella
      $w[$nos-2] = "t".$w[$nos-2];
    } else if ($thirdlast == "r" && $secondfirst == "r") { // kierrellä
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "n" && $secondfirst == "n") { // annella
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "m" && $secondfirst == "m") { // annella
      $w[$nos-2] = "p".mb_substr($w[$nos-2], 1);
    } else if ($thirdlast == "h" && $secondfirst == "d") { // hypähdellä
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    } else if (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // huudella
      $w[$nos-2] = "t".mb_substr($w[$nos-2], 1);
    }
    return $w;
  }
  
  /**
  * checks if the -ta verb conjugates like verb class 72
  */
  private function isVerbClass72($word) {
    if (preg_match("/(hap|par|mad)ata/", $word) ||
      // exclude fex. lanata
      preg_match("/(.*)(l|n|p|h|y|s|a|lv|ai|aj|av|am|ed|ev|hk|hm|hv|id|ii|im|ir|iu|lm|lv|oj|or|nk|rj|rm|rk|yk|uj|ur)eta/", $word) ||
      // kyetä, paeta, norjeta, ranketa, edetä
      preg_match("/(.*)(aks|eik|eud|eik)ota/", $word) ||
      // heikota
      preg_match("/(.*)(al|ir|in|ja|lk|ll|lo|om|sa|rk|rv|uk|va)ita/", $word) ||
      // ansaita, harkita, hallita, häiritä, iloita, lukita, mainita
      preg_match("/(.*)(aa|ai|uo|ou|pe|pi)sta/", $word) ||
      // ehkäistä
      preg_match("/(.*)(arv|arj|eik|iev|imm|iuk|oiv|orj|ouk|urj|umm|ust|uum|val|yhj)eta/", $word) ||
      // himmetä, norjeta, tyhjetä
      preg_match("/(.*)(aks)uta/", $word)) {
      return true;
    }
    return false;
  }
  
  
  /**
  * return array with syllabuses
  */
  private function syllabs($word) {
    if (empty($word)) {
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
    for ($i = 0; $i < $loop;) {
      $d = 1; // how many digits forward
      if (in_array($w[$i], $cons)) {
        if (($i+1) >= $loop) { // if last is kons, remove the possible previous -
          $last = array_pop($com_word);
          if ($last != "-") {
            $com_word[] = $last;
          }
        }
        $com_word[] = $w[$i];
      } else if (in_array($w[$i], $wovels)) {
        $com_word[] = $w[$i];
        if (in_array($w[$i].$w[$i+1], $dif) || $w[$i] == $w[$i+1] || $w[$i+1] == "i") {
          // next diftongi, same vokaali or "i"
          $com_word[] = $w[$i+1];
          $d = 2;
          if (in_array($w[$i+2], $wovels)) {
            $com_word[] = "-";
          } else if (in_array($w[$i+2], $cons) && in_array($w[$i+3], $cons) && in_array($w[$i+4], $cons)) {
            $com_word[] = $w[$i+2];
            $com_word[] = $w[$i+3];
            $com_word[] = "-";
            $d = 4;
          } else if (in_array($w[$i+2], $cons) && in_array($w[$i+3], $cons)) {
            $com_word[] = $w[$i+2];
            $com_word[] = "-";
            $d = 3;
          } else if (in_array($w[$i+2], $cons)) {
            $com_word[] = "-";
            $d = 2;
          }
        } else if (in_array($w[$i+1], $wovels)) {
          $com_word[] = "-";
          $d = 1;
        } else {
          if (in_array($w[$i+1], $cons) && in_array($w[$i+2], $cons) && in_array($w[$i+3], $cons)) {
            $com_word[] = $w[$i+1];
            $com_word[] = $w[$i+2];
            $com_word[] = "-";
            $d = 3;
          } else if (in_array($w[$i+1], $cons) && in_array($w[$i+2], $cons)) {
            $com_word[] = $w[$i+1];
            $com_word[] = "-";
            $d = 2;
          } else if (in_array($w[$i+1], $cons)) {
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
    foreach ($com_word as $in => $letter) {
      if ($letter == "-") {
        $tindex++;
      } else if (isset($sylls[$tindex])) {
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
    } else if ($backVowelPos < $frontVowelPos) {  // both present frontWovel later
      $this->backVowelWord = false;
    }
    return $this->backVowelWord;
  }
} // end class
