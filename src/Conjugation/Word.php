<?php

namespace Conjugation;

class Word
{
    public const CONS = ["b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "z"];
    public const WOVELS = ["a", "e", "i", "o", "u", "y"];
    // diftongit
    private const DIFTONGS = ["yi", "ui", "oi", "ai", "ay", " au", "yo", "oy", "uo", "ou", "ie", "ei", "eu", "iu", "ey", "iy"];

    public const iToeBodyWords = [
        "hanki",
        "happi",
        "henki",
        "hirsi",
        "kampi",
        "kivi",
        "kumpi",
        "kuukausi",
        "kuusi",
        "kaikki",
        "käsi",
        "kieli",
        "kylki",
        "lahti",
        "lovi",
        "lampi",
        "lapsi",
        "lahti",
        "lehti",
        "luomi",
        "lumi",
        "mieli",
        "mäki",
        "nuoli",
        "onki",
        "ovi",
        "piki",
        "pieni",
        "retki",
        "saari",
        "sieni",
        "siipi",
        "sampi",
        "susi",
        "suksi",
        "sysi",
        "toimi",
        "tosi",
        "tuli",
        "tuuli",
        "veli",
        "vesi",
        "vuohi",
        "vuori",
        "vuosi",
        "ääni",
    ];

    private const GRADATION = [
        ["d" => "t"], // säde, sade
        ["k" => ""], // laki, reki
        // koe, aie ... kokeen
        ["k" => "kk"], // lomake
        ["p" => "v"], // siipi, huopa
        ["t" => "d"], // pata, mätä
        ["v" => "p"], // varvas
        ["s" => "d"], // vuosi

        ["kk" => "k"], // lakka
        ["pp" => "p"], // kappa
        ["tt" => "t"], // laatta, matto
        ["hd" => "nt"], // suhde
        ["ll" => "lt"], // puhallin
        ["lk" => "l"], // halko, palko
        ["lt" => "ll"], // pelto
        ["lp" => "lv"], // kylpy
        ["mm" => "mp"], // lammas
        ["mp" => "mm"], // kumpi, lampi, rampa
        ["ng" => "nk"], // penkere
        ["nk" => "ng"], // runko, vanki
        ["nn" => "nt"], // rinne
        ["ns" => "nn"], // ponsi
        ["nt" => "nn"], // ranta
        ["rk" => "rj"], // arki
        ["rt" => "rr"], // kerta, parta
        ["rv" => "rp"], // turve, tarve
    ];

    private const A = ["a", "ä"];
    private const O = ["o", "ö"];
    private const U = ["u", "y"];

    public function __construct($word)
    {
        $this->word = trim($word);
        $this->syllabs();
        $this->getGradation();
    }

    public function isCons(string $letter): bool
    {
        return in_array($letter, self::CONS, true);
    }
    public function isVowel(string $letter): bool
    {
        return in_array($letter, self::WOVELS, true);
    }

    public function getGradation(): void
    {

    }

    private function syllabs(): void
    {
        if (empty($this->word)) {
            return;
        }
        $this->orig = [];
        $orig = $this->word;

        // utf stuff. a:ksi ja o:ksi
        $word = str_replace(["å", "ä", "ö", "Å", "Ä", "Ö"], ["a", "a", "o", "a", "a", "o"], $this->word);
        $loop = mb_strlen($word);

        // split the word
        $w = str_split(mb_strtolower($word));
        $w[] = " "; // helpers so we dont get notice's
        $w[] = " ";
        $w[] = " ";
        $w[] = " ";

        // put the word here letters and -'s
        $com_word = [];
        for ($i = 0; $i < $loop;) {
            $d = 1; // how many digits forward
            if ($this->isCons($w[$i])) {
                if (($i + 1) >= $loop) { // if last is kons, remove the possible previous -
                    $last = array_pop($com_word);
                    if ($last != "-") {
                        $com_word[] = $last;
                    }
                }
                $com_word[] = $w[$i];
            } elseif ($this->isVowel($w[$i])) {
                $com_word[] = $w[$i];
                if (in_array($w[$i] . $w[$i + 1], self::DIFTONGS) || $w[$i] == $w[$i + 1] || $w[$i + 1] == "i") {
                  // next diftongi, same vowel or "i"
                    $com_word[] = $w[$i + 1];
                    $d = 2;
                    if ($this->isVowel($w[$i + 2])) {
                        $com_word[] = "-";
                    } elseif ($this->isCons($w[$i + 2]) && $this->isCons($w[$i + 3]) && $this->isCons($w[$i + 4])) {
                        $com_word[] = $w[$i + 2];
                        $com_word[] = $w[$i + 3];
                        $com_word[] = "-";
                        $d = 4;
                    } elseif ($this->isCons($w[$i + 2]) && $this->isCons($w[$i + 3])) {
                        $com_word[] = $w[$i + 2];
                        $com_word[] = "-";
                        $d = 3;
                    } elseif ($this->isCons($w[$i + 2])) {
                        $com_word[] = "-";
                        $d = 2;
                    }
                } elseif ($this->isVowel($w[$i + 1])) {
                    $com_word[] = "-";
                    $d = 1;
                } else {
                    if ($this->isCons($w[$i + 1]) && $this->isCons($w[$i + 2]) && $this->isCons($w[$i + 3])) {
                        $com_word[] = $w[$i + 1];
                        $com_word[] = $w[$i + 2];
                        $com_word[] = "-";
                        $d = 3;
                    } elseif ($this->isCons($w[$i + 1]) && $this->isCons($w[$i + 2])) {
                        $com_word[] = $w[$i + 1];
                        $com_word[] = "-";
                        $d = 2;
                    } elseif ($this->isCons($w[$i + 1])) {
                        $com_word[] = "-";
                        $d = 1;
                    }
                }
            }
            $i = $i + $d;
        }
        // now build the word back together
        $sylls = [];
        $tindex = 0;
        $windex = 0; // word index
        foreach ($com_word as $in => $letter) {
            if ($letter == "-") {
                $tindex++;
            } elseif (isset($sylls[$tindex])) {
                $sylls[$tindex] .= $letter;
                $this->orig[$tindex] .= mb_substr($orig, $windex, 1);
                $windex++;
            } else {
                $sylls[$tindex] = $letter;
                $this->orig[$tindex] = mb_substr($orig, $windex, 1);
                $windex++;
            }
        }
        $this->nbrOfSyllabuses = $tindex + 1;
        $this->sylls = $sylls;
    }


    /**
     *
     *
     *
     *
     *
     *
     *
     *
     */




    public function getSyllabus(int $index): string
    {
        return (isset($this->sylls[$index])) ? $this->sylls[$index] : "";
    }

    public function getNumberOfSyllabuses(): int
    {
        return $this->nbrOfSyllabuses;
    }

    public function getSylls(): array
    {
        return $this->sylls;
    }

    public function getOrig(): array
    {
        return $this->orig;
    }

    public function getLastSyllabus(): string
    {
        return $this->getSyllabus($this->getNumberOfSyllabuses() - 1);
    }

    public function getSecondToLastSyllabus(): string
    {
        return $this->getSyllabus($this->getNumberOfSyllabuses() - 2);
    }


    public function lastLetter(): ?string
    {
        return mb_substr($this->getLastSyllabus(), -1, 1);
    }
    public function firstLetterInLastSyllabus(): ?string
    {
        return mb_substr($this->getLastSyllabus(), 0, 1);
    }

    public function secondToLastLetterInLastSyllabus(): ?string
    {
        return mb_substr($this->getLastSyllabus(), -2, 1);
    }

    public function lastLetterInSecondToLastSyllabus(): ?string
    {
        return mb_substr($this->getSecondToLastSyllabus(), -1, 1);
    }

    public function lastDiftong(): ?string
    {
        return mb_substr($this->getSecondToLastSyllabus(), -1, 1) . mb_substr($this->getLastSyllabus(), 0, 1);
    }

    public function replaceFirstLetterOfLastSyllabus(string $with): void
    {
        $index = $this->getNumberOfSyllabuses() - 1;
        $this->sylls[$index] = $with . mb_substr($this->getLastSyllabus(), 1);
        $this->orig[$index] = $with . mb_substr($this->orig[$index], 1);
    }

    public function replaceLastLetter(string $with): void
    {
        $index = $this->getNumberOfSyllabuses() - 1;
        $this->sylls[$index] = mb_substr($this->getLastSyllabus(), 0, -1) . $with;
        $this->orig[$index] = mb_substr($this->orig[$index], 0, -1) . $with;
    }

    public function replaceLastSyllabus(string $with): void
    {
        $index = $this->getNumberOfSyllabuses() - 1;
        $this->sylls[$index] = $with;
        $this->orig[$index] = $with;
    }

    public function removeLastLetterFromSecondToLastSyllabus(): void
    {
        $index = $this->getNumberOfSyllabuses() - 2;
        $this->sylls[$index] = mb_substr($this->sylls[$index], 0, -1);
        $this->orig[$index] = mb_substr($this->orig[$index], 0, -1);
    }

    public function removeLastLetter(): void
    {
        $index = $this->getNumberOfSyllabuses() - 1;
        $this->sylls[$index] = mb_substr($this->sylls[$index], 0, -1);
        $this->orig[$index] = mb_substr($this->orig[$index], 0, -1);
    }

    public function returnWord(): string
    {
        return implode("", $this->getOrig());
    }
}
