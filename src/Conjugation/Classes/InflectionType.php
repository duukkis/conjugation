<?php

namespace Conjugation\Classes;

class InflectionType {
    public $kotusClasses;
    public $joukahainenClasses;
    public $gradation;
    public $matchWord;
    public $rmsfx;
    public array $inflectionRules;
    public $note;

    public function removeSuffix(string $word): string
    {
        if (!isset($this->rmsfx)) {
            return $word;
        }
        $l = mb_strlen($this->rmsfx);

        if ($l == 0) {
            return $word;
        } elseif (mb_strlen($word) <= $l) {
            return "";
        } else {
            return mb_substr($word, 0, -$l);
        }
    }
}