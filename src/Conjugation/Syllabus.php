<?php
namespace Conjugation;

class Syllabus
{
    private string $word;
    private array $sylls;
    private array $orig;
    private int $nbrOfSyllabuses = 0;

    CONST CONS = ["b", "c", "d", "f", "g", "h", "j", "k", "l", "m", "n", "p", "q", "r", "s", "t", "v", "w", "x", "z"];
    CONST WOVELS = ["a", "e", "i", "o", "u", "y"];
    // diftongit
    CONST DIFTONGS = ["yi", "ui", "oi", "ai", "ay", " au", "yo", "oy", "uo", "ou", "ie", "ei", "eu", "iu", "ey", "iy"];

    public function __construct($word)
    {
        $this->word = trim($word);
        $this->syllabs();
    }

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
          if (in_array($w[$i], self::CONS)) {
            if (($i+1) >= $loop) { // if last is kons, remove the possible previous -
              $last = array_pop($com_word);
              if ($last != "-") {
                $com_word[] = $last;
              }
            }
            $com_word[] = $w[$i];
          } else if (in_array($w[$i], self::WOVELS)) {
            $com_word[] = $w[$i];
            if (in_array($w[$i].$w[$i+1], self::DIFTONGS) || $w[$i] == $w[$i+1] || $w[$i+1] == "i") {
              // next diftongi, same vowel or "i"
              $com_word[] = $w[$i+1];
              $d = 2;
              if (in_array($w[$i+2], self::WOVELS)) {
                $com_word[] = "-";
              } else if (in_array($w[$i+2], self::CONS) && in_array($w[$i+3], self::CONS) && in_array($w[$i+4], self::CONS)) {
                $com_word[] = $w[$i+2];
                $com_word[] = $w[$i+3];
                $com_word[] = "-";
                $d = 4;
              } else if (in_array($w[$i+2], self::CONS) && in_array($w[$i+3], self::CONS)) {
                $com_word[] = $w[$i+2];
                $com_word[] = "-";
                $d = 3;
              } else if (in_array($w[$i+2], self::CONS)) {
                $com_word[] = "-";
                $d = 2;
              }
            } else if (in_array($w[$i+1], self::WOVELS)) {
              $com_word[] = "-";
              $d = 1;
            } else {
              if (in_array($w[$i+1], self::CONS) && in_array($w[$i+2], self::CONS) && in_array($w[$i+3], self::CONS)) {
                $com_word[] = $w[$i+1];
                $com_word[] = $w[$i+2];
                $com_word[] = "-";
                $d = 3;
              } else if (in_array($w[$i+1], self::CONS) && in_array($w[$i+2], self::CONS)) {
                $com_word[] = $w[$i+1];
                $com_word[] = "-";
                $d = 2;
              } else if (in_array($w[$i+1], self::CONS)) {
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
        $this->nbrOfSyllabuses = $tindex + 1;
        $this->sylls = $sylls;
    }
}