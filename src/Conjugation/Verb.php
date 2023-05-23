<?php

namespace Conjugation;

/**
* conjugate verbs
*/
class Verb
{
    private const E = "e";
    private const I = "i";
  // word we are conjugating
    private ?string $word = null;

  // is the word bach vowel word, if false then front wovel word
    private ?bool $backVowelWord = null;
  // ender we use minä haistan > n
    private string $ender = "";

    private array $wovels = ["a", "e", "i", "o", "u", "y", "ä", "ö"];
    private array $backWovels = ["a", "o", "u"];
    private array $frontWovels = ["y", "ä", "ö"];

    private string $a;
    private string $o;

    private Syllabus $syllabus;
  /**
  * constructor
  * @return void
  */
    public function __construct(?string $word = null)
    {
        if ($word !== null) {
            $this->init($word);
        }
    }

    private function init(string $word = null): string
    {
        $this->isBackWovelWord($word);
        if ($this->backVowelWord) {
            $this->a = "a";
            $this->o = "o";
        } else {
            $this->a = "ä";
            $this->o = "ö";
        }
        $this->word = $word;
        $this->syllabus = new Syllabus($word);
        return str_replace(["ä", "ö", "å"], ["a", "o", "a"], $word);
    }

    public function preesensMe(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "n";
        return $this->conjugatePreesensWithGradation($word, $this->a);
    }

    public function preesensYou(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "t";
        return $this->conjugatePreesensWithGradation($word, $this->a);
    }

    public function preesensSHe(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "";
        return $this->conjugatePreesensWithoutGradation($word, $this->a . $this->a, "ee");
    }

    public function preesensPluralWe(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "mme";
        return $this->conjugatePreesensWithGradation($word, $this->a);
    }

    public function preesensPluralYou(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "tte";
        return $this->conjugatePreesensWithGradation($word, $this->a);
    }

    public function preesensPluralThey(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "v" . $this->a . "t";
        return $this->conjugatePreesensWithoutGradation($word, $this->a, "e");
    }

    public function imperfectMe(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "n";
        return $this->conjugateImperfectWithGradation($word, $this->o);
    }

    public function imperfectYou(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "t";
        return $this->conjugateImperfectWithGradation($word, $this->o);
    }

    public function imperfectSHe(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "";
        return $this->conjugateImperfectWithoutGradation($word, $this->o);
    }

    public function imperfectPluralWe(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "mme";
        return $this->conjugateImperfectWithGradation($word, $this->o);
    }

    public function imperfectPluralYou(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "tte";
        return $this->conjugateImperfectWithGradation($word, $this->o);
    }

    public function imperfectPluralThey(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "v" . $this->a . "t";
        return $this->conjugateImperfectWithoutGradation($word, $this->o);
    }

    public function perfectSingle(string $word): string
    {
        $word = $this->init($word);
        if ($this->backVowelWord) {
            $this->ender = "nut";
            return $this->conjugatePerfect($word);
        } else {
            $this->ender = "nyt";
            return $this->conjugatePerfect($word);
        }
    }

    public function perfectPlural(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "neet";
        return $this->conjugatePerfect($word);
    }

    public function imperativeSingle(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "";
        return $this->conjugateImperativeSingle($word, $this->a);
    }

    public function imperativePlural(string $word): string
    {
        $word = $this->init($word);
        $this->ender = "k" . $this->a . $this->a;
        return $this->conjugateImperativePlural();
    }

  /**
   * PREESENS
   * me, you, we, you
   * @param string $word
   * @param string $a
   * @return string
   */
    private function conjugatePreesensWithGradation(string $word, string $a): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
      // aak-kos-taa
      // $secondlast = aakko(s)taa
      // $secondfirst = aak(k)ostaa
      // thirdlast = aa(k)kostaa
        if ($nos >= 3) {
            $thirdlast = mb_substr($this->syllabus->getSyllabus($nos - 3), -1);
        } else {
            $thirdlast = "";
        }
        $secondfirst = mb_substr($this->syllabus->getSyllabus($nos - 2), 0, 1);
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                $w = $this->taaVerb($w, $nos, $secondlast, $a);
                $w[$nos - 1] .= $this->ender;
                break;
            case "da": // dä
                if ($secondlast == "h") { // tehdä, nähdä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1);
                    $w[$nos - 1] = self::E . $this->ender;
                } else {
                    $w[$nos - 1] = $this->ender;
                }
                break;
            case "la": // lä
                $w = $this->laVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = self::E . $this->ender;
                break;
            case "ta": // tä
              // verb match class 72
                if ($this->isVerbClass72($word)) {
                    $w[$nos - 1] = "";
                    $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);

                  // if last is wovel then nen, else en
                    if (in_array(mb_substr($w[$nos - 2], -1), $this->wovels)) {
                        $w[$nos - 1] = "n" . self::E . $this->ender;
                    } else {
                        $w[$nos - 1] = self::E . $this->ender;
                    }
                } else { // verb class 74
                    $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                    $w[$nos - 1] = $a . $this->ender;
                }
                break;
            case "a": // ä
                $w = $this->aVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = $this->ender;
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = $a . $this->ender;
                break;
            case "na": // mennä
            case "ra": // purra
                $w[$nos - 1] = self::E . $this->ender;
                break;
            case "kaa": // alkaa, jakaa
                if (in_array($secondlast, array("l", "a", "r"))) { // alkaa, jakaa, purkaa
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 1, -1) . $this->ender;
                } else { // jatkaa
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                }
                break;
            case "jaa": // ajaa
            case "nee": // tarkenee
            case "laa": // palaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
        }

        return $this->buildWord($w);
    }

  /**
  * PREESENS
  * he, she, they
  * @param string $word
  * @param string $a - either a or ä
  * @param string $e - either e or ee
  * @return string
  */
    private function conjugatePreesensWithoutGradation(string $word, string $a, string $e): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
      // aak-kos-taa
      // $secondlast = aakko(s)taa
      // $secondfirst = aak(k)ostaa
      // thirdlast = aa(k)kostaa
        if ($nos >= 3) {
            $thirdlast = mb_substr($this->syllabus->getSyllabus($nos - 3), -1);
        } else {
            $thirdlast = "";
        }
        $secondfirst = mb_substr($this->syllabus->getSyllabus($nos - 2), 0, 1);
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                $w[$nos - 1] = "t" . $a . $this->ender;
                break;
            case "da": // dä
                if ($secondlast == "h") { // tehdä, nähdä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1);
                    $w[$nos - 1] = "k" . $e . $this->ender;
                } else {
                    $w[$nos - 1] = $this->ender;
                }
                break;
            case "la": // lä
                $w = $this->laVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = $e . $this->ender;
                break;
            case "ta": // tä
              // verb match class 72
                if ($this->isVerbClass72($word)) {
                    $w[$nos - 1] = "";
                    $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                  // if last is wovel then nen, else en
                    if (in_array(mb_substr($w[$nos - 2], -1), $this->wovels)) {
                        $w[$nos - 1] = "n" . $e . $this->ender;
                    } else {
                        $w[$nos - 1] = $e . $this->ender;
                    }
                } else { // verb class 74
                    $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                    if ($secondlast == "a" || $secondlast == "ä" && mb_strlen($a) == 2) {
                        $a = mb_substr($a, 0, 1);
                    }
                    $w[$nos - 1] = $a . $this->ender;
                }
                break;
            case "a": // ä
                if (!empty($this->ender)) {
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                } else { // rest jäätyä > jäätyy, kaatua > kaatua
                    $w[$nos - 1] = mb_substr($w[$nos - 2], -1);
                }
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = $a . $this->ender;
                break;
            case "na": // mennä
            case "ra": // purra
                $w[$nos - 1] = $e . $this->ender;
                break;
            case "kaa": // alkaa, jakaa
                break;
            case "jaa": // ajaa
            case "nee": // tarkenee
            case "laa": // palaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                if (!empty($this->ender)) {
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                }
                break;
        }
        return $this->buildWord($w);
    }

  /**
   * IMPERFECT
   * me, you, we, you
   * @param $word
   * @param $o - either o or ö
   * @return string
   */
    private function conjugateImperfectWithGradation($word, $o): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
      // aak-kos-taa
      // $secondlast = aakko(s)taa
      // $secondfirst = aak(k)ostaa
      // thirdlast = aa(k)kostaa
        if ($nos >= 3) {
            $thirdlast = mb_substr($this->syllabus->getSyllabus($nos - 3), -1);
        } else {
            $thirdlast = "";
        }
        $secondfirst = mb_substr($this->syllabus->getSyllabus($nos - 2), 0, 1);
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);
        $secondlasttwo = mb_substr($this->syllabus->getSyllabus($nos - 2), -2);

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                if ($secondlast == "t" && $thirdlast == "t") { // laittaa
                    $w[$nos - 1] = self::I;
                } elseif (in_array($w[$nos - 2], array("tie", "kään", "pyy", "tai"))) { // tietää
                    $w[$nos - 1] = "s" . self::I;
                } elseif (in_array($w[$nos - 2], array("vaih"))) { // vaihtaa
                    $w[$nos - 1] = "d" . $o . self::I;
                } elseif ($secondlast == "h" || in_array($secondlast, $this->wovels)) { // johtaa
                    $w[$nos - 1] = "d" . self::I;
                } elseif (in_array($w[$nos - 2], array("an", "kan"))) {
                  // antaa
                    $w[$nos - 1] = "n" . $o . self::I;
                } elseif (in_array($w[$nos - 2], array("aut", "lait", "saat", "kat"))) {
                  // auttaa
                    $w[$nos - 1] = $o . self::I;
                } elseif ($secondlast == "n") { // juontaa
                    $w[$nos - 1] = "s" . self::I;
                } elseif ($secondlast == "r") { // avartaa
                    $w[$nos - 1] = "s" . self::I;
                } elseif ($secondlast == "s") { // vastustaa
                    $w[$nos - 1] = "t" . self::I;
                } elseif ($secondlast == "l") { // kiiltää
                    $w[$nos - 1] = "s" . self::I;
                } else {
                    $w[$nos - 1] = self::I;
                }
                $w[$nos - 1] .= $this->ender;
                break;
            case "da": // dä
                if ($secondlast == "h") { // tehdä, nähdä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1) . self::I;
                } elseif ($secondlasttwo == "ay") { // käydä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1) . "v" . self::I;
                } elseif ($secondlasttwo == "uo") { // juoda
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, 1) . $o . self::I;
                } elseif ($secondlasttwo == "yo") { // lyödä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, 1) . $o . self::I;
                } elseif ($secondlasttwo == "ie") { // viedä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, 1) . "e" . self::I;
                } elseif (in_array($secondlasttwo, array("aa", "ää", "oo", "uu", "yy"))) { // saada, myydä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1) . self::I;
                } elseif ($secondlast != self::I) {
                    $w[$nos - 1] .= self::I;
                }
                $w[$nos - 1] = $this->ender;
                break;
            case "la": // lä
                $w = $this->laVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = self::I . $this->ender;
                break;
            case "ta": // tä
              // TODO:
              // verb match class 72
                if ($this->isVerbClass72($word)) {
                    $w[$nos - 1] = "";
                    $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                  // if last is wovel then nen, else en
                    if (in_array(mb_substr($w[$nos - 2], -1), $this->wovels)) {
                        $w[$nos - 1] = "n" . self::I . $this->ender;
                    } else {
                        $w[$nos - 1] = self::I . $this->ender;
                    }
                } else { // verb class 74
                    $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                    $w[$nos - 1] = "s" . self::I . $this->ender;
                }
                break;
            case "a": // ä
                $w = $this->aVerb($w, $nos, $secondfirst, $thirdlast);
                if (mb_substr($w[$nos - 2], -1) == "e") {
                    if ($word == "tuntea") {
                        $w[$nos - 2] = "s";
                    } else {
                        $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1); // remove e
                    }
                    $w[$nos - 1] = self::I . $this->ender;
                } elseif (mb_substr($w[$nos - 2], -1) != self::I) {
                    $w[$nos - 1] = self::I . $this->ender;
                } else {
                    $w[$nos - 1] = $this->ender;
                }
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 2) . "s" . self::I . $this->ender;
                break;
            case "na": // mennä
            case "ra": // purra
                $w[$nos - 1] = self::I . $this->ender;
                break;
            case "kaa": // alkaa, jakaa
                if (in_array($secondlast, array("l", "a"))) { // alkaa, jakaa
                    $w[$nos - 1] = $o . self::I . $this->ender;
                } elseif (in_array($secondlast, array("r"))) { // purkaa
                    $w[$nos - 1] = self::I . $this->ender;
                } else { // jatkaa
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . $o . self::I . $this->ender;
                }
                break;
            case "laa":
                if ($w[$nos - 2] == "e") { // elää
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . self::I . $this->ender;
                } else { // palaa etc
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . $o . self::I . $this->ender;
                }
                break;
            case "nee": // tarkenee
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . self::I . $this->ender;
                break;
            case "jaa": // ajaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . $o . self::I . $this->ender;
                break;
        }

        return $this->buildWord($w);
    }

  /**
   * IMPERFECT
   * he, they
   * @param $word
   * @param $o - o or ö
   * @return string
   */
    private function conjugateImperfectWithoutGradation(string $word, string $o): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
      // aak-kos-taa
      // $secondlast = aakko(s)taa
      // $secondfirst = aak(k)ostaa
      // thirdlast = aa(k)kostaa
        if ($nos >= 3) {
            $thirdlast = mb_substr($this->syllabus->getSyllabus($nos - 3), -1);
        } else {
            $thirdlast = "";
        }
        $secondfirst = mb_substr($this->syllabus->getSyllabus($nos - 2), 0, 1);
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);
        $secondlasttwo = mb_substr($this->syllabus->getSyllabus($nos - 2), -2);

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                if (in_array($this->syllabus->getSyllabus($nos - 2), array("an", "kan", "ut", "lait", "saat", "kat", "mah", "vaih"))) {
                    $w[$nos - 1] = "t" . $o . self::I . $this->ender;
                } elseif (in_array($this->syllabus->getSyllabus($nos - 2), array("len", "sen", "kiel", "kier", "kaan", "jen", "ler", "loy", "myon", "ran", "nen", "piir", "hal", "pyy", "ken", "rien", "siir", "sal", "vel", "kel", "tai", "tie", "tyon", "den", "kal", "uur", "el", "hen", "mar"))) {
                    $w[$nos - 1] = "s" . self::I . $this->ender;
                } else {
                    $w[$nos - 1] = "t" . self::I . $this->ender;
                }
                break;
            case "da": // dä
                $w[$nos - 1] = $this->ender;

                if ($secondlast == "h") { // tehdä, nähdä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1);
                    $w[$nos - 1] = "k" . self::I . $this->ender;
                } elseif ($secondlasttwo == "ay") { // käydä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1) . "v" . self::I;
                } elseif ($secondlasttwo == "uo") { // juoda
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, 1) . $o . self::I;
                } elseif ($secondlasttwo == "yo") { // lyödä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, 1) . $o . self::I;
                } elseif ($secondlasttwo == "ie") { // viedä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, 1) . "e" . self::I;
                } elseif (in_array($secondlasttwo, array("aa", "ää", "oo", "uu", "yy"))) {
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1);
                    $w[$nos - 1] = self::I . $this->ender;
                } elseif ($secondlast != self::I) {
                    $w[$nos - 1] = self::I . $this->ender;
                }
                break;
            case "la": // lä
                $w = $this->laVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = self::I . $this->ender;
                break;
            case "ta": // tä
              // verb match class 72
                if ($this->isVerbClass72($word)) {
                    $w[$nos - 1] = "";
                    $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                  // if last is wovel then nen, else en
                    if (in_array(mb_substr($w[$nos - 2], -1), $this->wovels)) {
                        $w[$nos - 1] = "n" . self::I . $this->ender;
                    } else {
                        $w[$nos - 1] = self::I . $this->ender;
                    }
                } else { // verb class 74
                    $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                    $w[$nos - 1] = "s" . self::I . $this->ender;
                }
                break;
            case "a": // ä
                if ($secondlast == "e") {
                    if ($word == "tuntea") {
                        $w[$nos - 2] = "s";
                    } else {
                        $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1); // remove e
                    }
                }
                $w[$nos - 1] = "";
                if (mb_substr($w[$nos - 2], -1) != self::I) {
                    $w[$nos - 1] = self::I;
                }
                $w[$nos - 1] .= $this->ender;
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . "s" . self::I . $this->ender;
                break;
            case "na": // mennä
            case "ra": // purra
                $w[$nos - 1] = self::I . $this->ender;
                break;
            case "kaa": // alkaa, jakaa, purkaa
                if ($w[$nos - 2] == "pur") {
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . self::I . $this->ender;
                } else {
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . $o . self::I . $this->ender;
                }
                break;
            case "jaa": // ajaa
            case "nee": // tarkenee
            case "laa": // palaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                if ($w[$nos - 2] == "e") {
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . self::I . $this->ender;
                } else {
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, 1) . $o . self::I . $this->ender;
                }
                break;
        }

        return $this->buildWord($w);
    }

  /**
   * PERFECT
   * me, you, he/she
   * @return string
   */
    private function conjugatePerfect(string $word): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
        $secondfirst = mb_substr($this->syllabus->getSyllabus($nos - 2), 0, 1);
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);
        if ($nos >= 3) {
            $thirdlast = mb_substr($this->syllabus->getSyllabus($nos - 3), -1);
        } else {
            $thirdlast = "";
        }

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
            case "da": // dä
                $w[$nos - 1] = $this->ender;
                break;
            case "la": // lä
                $w[$nos - 1] = "l" . mb_substr($this->ender, 1);
                break;
            case "ta": // tä
                if ($this->isVerbClass72($word)) {
                    $w[$nos - 1] = "";
                    $w = $this->taVerb72ForPerfect($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                  // if last is wovel then nnut, else nut
                    if (in_array(mb_substr($w[$nos - 2], -1), $this->wovels)) {
                        $w[$nos - 1] = "n" . $this->ender;
                    } else {
                        $w[$nos - 1] = mb_substr($this->ender, 1);
                    }
                } else { // verb class 74
                  // kontata, rynnätä
                    $w[$nos - 1] = "n" . $this->ender;
                }
                break;
            case "a": // ä
                $w[$nos - 1] = $this->ender;
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
            case "na": // mennä
                $w[$nos - 1] = "n" . mb_substr($this->ender, 1);
                break;
            case "ra": // purra
                $w[$nos - 1] = "r" . mb_substr($this->ender, 1);
                break;
            case "kaa": // alkaa, jakaa, purkaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
            case "jaa": // ajaa
            case "nee": // tarkenee
            case "laa": // palaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
        }

        return $this->buildWord($w);
    }

  /**
   * IMPERATIVE
   * you, you
   * @param string $word
   * @param string $a
   * @return string
   */
    private function conjugateImperativeSingle(string $word, string $a): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
      // aak-kos-taa
      // $secondlast = aakko(s)taa
      // $secondfirst = aak(k)ostaa
      // thirdlast = aa(k)kostaa
        if ($nos >= 3) {
            $thirdlast = mb_substr($this->syllabus->getSyllabus($nos - 3), -1);
        } else {
            $thirdlast = "";
        }
        $secondfirst = mb_substr($this->syllabus->getSyllabus($nos - 2), 0, 1);
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                $w = $this->taaVerb($w, $nos, $secondlast, $a);
                $w[$nos - 1] .= $this->ender;
                break;
            case "da": // dä
                if ($secondlast == "h") { // tehdä, nähdä
                    $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1);
                    $w[$nos - 1] = self::E . $this->ender;
                } else {
                    $w[$nos - 1] = $this->ender;
                }
                break;
            case "la": // lä
                $w = $this->laVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = self::E . $this->ender;
                break;
            case "ta": // tä
              // verb match class 72
                if ($this->isVerbClass72($word)) {
                    $w[$nos - 1] = "";
                    $w = $this->taVerb72($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);

                  // if last is wovel then nen, else en
                    if (in_array(mb_substr($w[$nos - 2], -1), $this->wovels)) {
                        $w[$nos - 1] = "n" . self::E . $this->ender;
                    } else {
                        $w[$nos - 1] = self::E . $this->ender;
                    }
                } else { // verb class 74
                    $w = $this->taVerb74($word, $w, $nos, $secondfirst, $secondlast, $thirdlast);
                    $w[$nos - 1] = $a . $this->ender;
                }
                break;
            case "a": // ä
                $w = $this->aVerb($w, $nos, $secondfirst, $thirdlast);
                $w[$nos - 1] = $this->ender;
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = $a . $this->ender;
                break;
            case "na": // mennä
            case "ra": // purra
                $w[$nos - 1] = self::E . $this->ender;
                break;
            case "kaa": // alkaa, jakaa
                if (in_array($secondlast, array("l", "a", "r"))) { // alkaa, jakaa, purkaa
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 1, -1) . $this->ender;
                } else { // jatkaa
                    $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                }
                break;
            case "jaa": // ajaa
            case "nee": // tarkenee
            case "laa": // palaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
        }

        return $this->buildWord($w);
    }

  /**
   * PERFECT
   * me, you, he/she
   * @return string
   */
    private function conjugateImperativePlural(): string
    {
        $w = $this->syllabus->getOrig();
        $nos = $this->syllabus->getNumberOfSyllabuses();
        $secondlast = mb_substr($this->syllabus->getSyllabus($nos - 2), -1);

        switch ($this->syllabus->getLastSyllabus()) {
            case "taa": // tää
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
            case "da": // dä
                $w[$nos - 1] = $this->ender;
                break;
            case "la": // lä
                $w[$nos - 1] = $this->ender;
                break;
            case "ta": // tä
              // aidata
                if (in_array($secondlast, $this->wovels)) {
                    $w[$nos - 1] = "t" . $this->ender;
                } else { // ehkäistä
                    $w[$nos - 1] = $this->ender;
                }
                break;
            case "a": // ä
                $w[$nos - 1] = $this->ender;
                break;
            case "paa": // lappaa, nappaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
            case "na": // mennä
                $w[$nos - 1] = "n" . mb_substr($this->ender, 1);
                break;
            case "ra": // purra
                $w[$nos - 1] = "r" . mb_substr($this->ender, 1);
                break;
            case "kaa": // alkaa, jakaa, purkaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
            case "jaa": // ajaa
            case "nee": // tarkenee
            case "laa": // palaa
            default: // jaksaa, maksaa, jauhaa, kalvaa, nauraa, painaa
                $w[$nos - 1] = mb_substr($w[$nos - 1], 0, -1) . $this->ender;
                break;
        }

        return $this->buildWord($w);
    }

  /**
  * build the final word we can return
  */
    private function buildWord(array $w): string
    {
        return implode('', $w);
    }

    private function taaVerb(array $w, int $nos, string $secondlast, string $a): array
    {
        if ($secondlast == "t") { // laittaa
            $w[$nos - 1] = $a;
        } elseif ($secondlast == "h" || in_array($secondlast, $this->wovels)) { // johtaa
            $w[$nos - 1] = "d" . $a;
        } elseif ($secondlast == "n") { // juontaa
            $w[$nos - 1] = "n" . $a;
        } elseif ($secondlast == "r") { // avartaa
            $w[$nos - 1] = "r" . $a;
        } elseif ($secondlast == "l") { // kiiltää
            $w[$nos - 1] = "l" . $a;
        } else {
            $w[$nos - 1] = "t" . $a;
        }
        return $w;
    }

  /**
  * conjugation for ta-verbs 72
  */
    private function taVerb72(string $word, array $w, int $nos, string $secondfirst, string $secondlast, string $thirdlast): array
    {
        if ($word == "juosta") { // juosta
            $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1) . "ks";
        } elseif ($secondfirst == "d") { // pidetä
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1) . "n";
        } elseif ($secondlast == "s") { // hotkaista, nousta
          // nothing
        } elseif ($secondlast == "i") { // ravita, suvaita, valita
            $w[$nos - 2] .= "ts";
        } elseif ($thirdlast == "r" && $secondfirst == "j") { // tarjeta
            $w[$nos - 2] = "k" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "v") { // kaveta
            $w[$nos - 2] = "p" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // suipeta
            $w[$nos - 2] = "p" . $w[$nos - 2];
        } elseif (in_array($w[$nos - 2], array("ke", "e"))) { // vanketa, paeta
            $w[$nos - 2] = "k" . $w[$nos - 2];
        }
        return $w;
    }

  /**
  * conjugation for ta-verbs 72
  */
    private function taVerb72ForPerfect(string $word, array $w, int $nos, string $secondfirst, string $secondlast, string $thirdlast): array
    {
        if ($word == "juosta") { // juosta
            $w[$nos - 2] = mb_substr($w[$nos - 2], 0, -1) . "ks";
        } elseif ($secondfirst == "d") { // pidetä
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1) . "n";
        } elseif ($secondlast == "s") { // hotkaista, nousta, ehkäistä
            $w[$nos - 2] .= "s";
        } elseif ($thirdlast == "r" && $secondfirst == "j") { // tarjeta
            $w[$nos - 2] = "k" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "v") { // kaveta
            $w[$nos - 2] = "p" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // suipeta
            $w[$nos - 2] = "p" . $w[$nos - 2];
        }
        return $w;
    }

  /**
  * conjugation for ta-verbs 74
  */
    private function taVerb74(string $word, array $w, int $nos, string $secondfirst, string $secondlast, string $thirdlast): array
    {
        if ($secondlast == "y" && isset($w[$nos - 3]) && mb_strlen($w[$nos - 3]) == 2) { // lymytä, rymytä, kärytä
          // nothing, check if necessary
        } elseif ($secondlast == "y") { // ryöpytä, röyhytä, löylytä
            $w[$nos - 2] .= "t";
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // loitota, mitata
            $w[$nos - 2] = "t" . $w[$nos - 2];
        } elseif ($thirdlast == "n" && $secondfirst == "t") { // kontata
            $w[$nos - 2] = "t" . $w[$nos - 2];
        } elseif ($thirdlast == "n" && $secondfirst == "n") { // rynnata
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // hakata
            $w[$nos - 2] = "k" . $w[$nos - 2];
        } elseif ($thirdlast == "r" && $secondfirst == "k") { // virkata
            $w[$nos - 2] = "k" . $w[$nos - 2];
        } elseif (in_array($word, array("varata", "kelata"))) {
          // nothing
        } elseif (in_array($thirdlast, array("y", "e")) && mb_strlen($w[$nos - 3]) == 2 && $secondfirst == "l") {
          // hylätä, pelätä
            $w[$nos - 2] = "lk" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // hypätä
            $w[$nos - 2] = "p" . $w[$nos - 2];
        } elseif (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos - 3]) == 2 && $w[$nos - 2] == "vi") { // hävitä
          // nothing
        } elseif (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos - 3]) == 2 && $w[$nos - 2] == "ve") { // ruveta
            $w[$nos - 2] = "p" . mb_substr($w[$nos - 2], 1);
        } elseif (
            $secondfirst == "v" &&
            isset($w[$nos - 3]) && in_array($w[$nos - 3], array("le", "lu", "ta", "kai", "kel", "kii"))
        ) {
            $w[$nos - 2] = "p" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // aidata
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "r" && $secondfirst == "j") { // herjetä / tarjota which way to go
          // $w[$nos-2] = "k".mb_substr($w[$nos-2], 1);
        } elseif ($thirdlast == "h" && $secondfirst == "j" && mb_strlen($w[$nos - 3]) == 3) { // puhjeta, exclude ohjata
            $w[$nos - 2] = "k" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "l" && $secondfirst == "j") { // teljetä
            $w[$nos - 2] = "k" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "y" && $secondfirst == "e") { // kyetä
            $w[$nos - 2] = "k" . $w[$nos - 2];
        } elseif ($nos >= 3 && mb_strlen($w[$nos - 3]) == 1 && $secondfirst == "h") { // uhata
            $w[$nos - 2] = "hk" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "a" && mb_strlen($w[$nos - 3]) == 2 && $secondfirst == "r") {
          // karata (not all vowels since kerätä), varata also bad
            $w[$nos - 2] = "rk" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "n" && $secondfirst == "g") { // hangata
            $w[$nos - 2] = "k" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "r" && $secondfirst == "r") { // irrota
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "l" && $secondfirst == "l") { // vallata
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // leudota
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "h" && $secondfirst == "d") { // kohdata
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "m" && $secondfirst == "m") { // kimmota
            $w[$nos - 2] = "p" . mb_substr($w[$nos - 2], 1);
        } elseif ($w[$nos - 2] == "taa") { // taata
            $w[$nos - 2] = "taka";
        } elseif ($w[$nos - 2] == "maa") { // maata
            $w[$nos - 2] = "maka";
        } elseif ($w[$nos - 2] == "koo") { // koota
            $w[$nos - 2] = "koko";
        }
        return $w;
    }

  /**
   * conjugation for a-verbs
   * @param array $w
   * @param int $nos
   * @param string $secondfirst
   * @param string $thirdlast
   * @return array
   */
    private function aVerb(array $w, int $nos, string $secondfirst, string $thirdlast): array
    {
        if ($thirdlast == "l" && $secondfirst == "p") { // kylpeä
            $w[$nos - 2] = "v" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "m" && $secondfirst == "p") { // empiä, ampua
            $w[$nos - 2] = "m" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "h" && $secondfirst == "t") { // ahnehtia, lähteä
            $w[$nos - 2] = "d" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // potea, kutea, päteä
            $w[$nos - 2] = "d" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "n" && $secondfirst == "t") { // jakaantua
            $w[$nos - 2] = "n" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "r" && $secondfirst == "t") { // kertoa
            $w[$nos - 2] = "r" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "l" && $secondfirst == "t") { // paleltua
            $w[$nos - 2] = "l" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "t" && $secondfirst == "t") { // asettua
            $w[$nos - 2] = mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "s" && $secondfirst == "t") { // arvopaperistua, poistua
          // stays the same
        } elseif ($thirdlast == "n" && $secondfirst == "k") { // henkiä, penkoa
            $w[$nos - 2] = "g" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "l" && $secondfirst == "k") { // hylkiä
            $w[$nos - 2] = "j" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "r" && $secondfirst == "k") { // särkeä, pyrkiä
            $w[$nos - 2] = mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // hakea, kokea
            $w[$nos - 2] = mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "k" && $secondfirst == "k") { // hankkia
            $w[$nos - 2] = mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "p" && $secondfirst == "p") { // harppoa, oppia
            $w[$nos - 2] = mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "p") { // hiipiä, kaapia, ruopia, ...
            $w[$nos - 2] = "v" . mb_substr($w[$nos - 2], 1);
        } elseif ($this->syllabus->getSyllabus($nos - 2) == "tu") { // kaatua
            $w[$nos - 2] = "du";
        } elseif ($this->syllabus->getSyllabus($nos - 2) == "ty") { // jäätyä
            $w[$nos - 2] = "dy";
        }
        return $w;
    }

  /**
   * conjugation for la-verbs
   * @param array $w
   * @param int $nos
   * @param string $secondfirst
   * @param string $thirdlast
   * @return array
   */
    private function laVerb(array $w, int $nos, string $secondfirst, string $thirdlast): array
    {
        if (in_array($thirdlast, $this->wovels) && $w[$nos - 2] == "tel") { // haukotella
            $w[$nos - 3] .= "t";
        } elseif ($thirdlast == "l" && $secondfirst == "l") { // takellella
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "k") { // nakella
          // nothing
        } elseif (in_array($thirdlast, $this->wovels) && mb_strlen($w[$nos - 3]) > 1 && $secondfirst == "p") { // tapella, exclude epäillä
            $w[$nos - 2] = "p" . $w[$nos - 2];
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "t") { // aatella
            $w[$nos - 2] = "t" . $w[$nos - 2];
        } elseif ($thirdlast == "r" && $secondfirst == "r") { // kierrellä
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "n" && $secondfirst == "n") { // annella
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "m" && $secondfirst == "m") { // annella
            $w[$nos - 2] = "p" . mb_substr($w[$nos - 2], 1);
        } elseif ($thirdlast == "h" && $secondfirst == "d") { // hypähdellä
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        } elseif (in_array($thirdlast, $this->wovels) && $secondfirst == "d") { // huudella
            $w[$nos - 2] = "t" . mb_substr($w[$nos - 2], 1);
        }
        return $w;
    }

  /**
  * checks if the -ta verb conjugates like verb class 72
  */
    private function isVerbClass72(string $word): bool
    {
        if (
            preg_match("/(hap|par|mad)ata/", $word) ||
            // exclude fex. lanata
            preg_match("/(.*)(l|n|p|h|y|s|a|ai|aj|av|am|ed|ev|hk|hm|hv|id|ii|im|ir|iu|lm|lv|oj|or|nk|rj|rm|rk|yk|uj|ur)eta/", $word) ||
            // kyetä, paeta, norjeta, ranketa, edetä
            preg_match("/(.*)(aks|eik|eud)ota/", $word) ||
            // heikota
            preg_match("/(.*)(al|ir|in|ja|lk|ll|lo|om|sa|rk|rv|uk|va)ita/", $word) ||
            // ansaita, harkita, hallita, häiritä, iloita, lukita, mainita
            preg_match("/(.*)(aa|ai|uo|ou|pe|pi)sta/", $word) ||
            // ehkäistä
            preg_match("/(.*)(arv|arj|eik|iev|imm|iuk|oiv|orj|ouk|urj|umm|ust|uum|val|yhj)eta/", $word) ||
            // himmetä, norjeta, tyhjetä
            preg_match("/(.*)(aks)uta/", $word)
        ) {
            return true;
        }
        return false;
    }

  /**
  * check if the word is back wovel word
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
}
