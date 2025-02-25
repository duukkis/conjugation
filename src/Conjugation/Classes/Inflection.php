<?php

namespace Conjugation\Classes;

use Conjugation\Enums\GradType;

class Inflection {

    /** @var array|InflectionType[] $nounTypes */
    public array $nounTypes = [];

    public function __construct()
    {
        $subst = file_get_contents(__DIR__ . "/../../resources/subst.aff");
        $lines = explode("\n", $subst);
        $this->nounTypes = $this->readInflectionTypes($lines);
    }


    /**
     * @param array<int, string> $arr
     * @return InflectionType[] array
     */
    public function readInflectionTypes(array $arr): array
    {
        $result = [];
        $lines = [];

        foreach ($arr as $line) {
            if (str_starts_with($line, '#')) {
                $line = "";
            } else {
                $line = trim($line);
            }
            if ($line !== '') {
                $lines[] = $line;
            }
        }

        foreach ($lines as $line) {
            $header_tuple = explode(":", $line);
            $header_tuple[0] = trim($header_tuple[0]);

            if (count($header_tuple) >= 2) {
                $header_tuple[1] = trim($header_tuple[1]);
                if (count($header_tuple) > 2) {
                    $header_tuple[1] .= ":".$header_tuple[2];
                }
            }
            switch ($header_tuple[0]) {
                case 'class':
                    $t = new InflectionType();
                    $t->kotusClasses = explode(",", $header_tuple[1]);
                    break;
                case 'sm-class':
                    $t->joukahainenClasses = explode(" ", $header_tuple[1]);
                    break;
                case 'rmsfx':
                    $t->rmsfx = $header_tuple[1];
                    break;
                case 'match-word':
                    $t->matchWord = $header_tuple[1];
                    break;
                case 'consonant-gradation':
                    if ($header_tuple[1] === '-') $t->gradation = GradType::GRAD_NONE;
                    if ($header_tuple[1] === 'sw') $t->gradation = GradType::GRAD_SW;
                    if ($header_tuple[1] === 'ws') $t->gradation = GradType::GRAD_WS;
                    break;
                case 'note':
                    $t->note = $header_tuple[1];
                    break;
                case 'end':
                    if ($header_tuple[1] === 'class') {
                        $result[] = $t;
                        $t = new InflectionType();
                    } else if ($header_tuple[1] === 'rules') {

                    }
                    break;
                case 'rules':
                    break;
                case 'group':
                case 'transform-group':
                    // skip these
                    break;
                default:
                    $rule = new InflectionRule();
                    $strippedLine = trim($line);
                    $columns = preg_split('/\s+/', $strippedLine);

                    if (str_starts_with($columns[0], '!')) {
                        $rule->name = mb_substr($columns[0], 1);
                        $rule->isCharacteristic = true;
                    } else {
                        $rule->name = $columns[0];
                        $rule->isCharacteristic = in_array($columns[0], [
                            'nominatiivi', 'genetiivi', 'partitiivi', 'illatiivi',
                            'nominatiivi_mon', 'genetiivi_mon', 'partitiivi_mon', 'illatiivi_mon',
                            'infinitiivi_1', 'preesens_yks_1', 'imperfekti_yks_3',
                            'kondit_yks_3', 'imperatiivi_yks_3', 'partisiippi_2',
                            'imperfekti_pass'
                        ]);
                    }

                    if ($columns[1] !== '0') {
                        $rule->delSuffix = $columns[1];
                    }
                    if ($columns[2] !== '0') {
                        $rule->addSuffix = $columns[2];
                    }
                    if ($columns[3] === 's') {
                        $rule->gradation = GradType::GRAD_STRONG;
                    }
                    if (count($columns) > 4) {
                        // skip r
                        if ($this->readOption($columns[4], 'ps', '') !== 'r') {
                            $rule->rulePriority = (int) $this->readOption($columns[4], 'prio', '1');
                        }
                    }
                    $t->inflectionRules[] = $rule;
                    break;
            }
        }
        return $result;
    }


    public function readOption($options, $name, $default) : string
    {
        $parts = explode(',', $options);

        foreach ($parts as $part) {
            $nameval = explode('=', $part);

            if (count($nameval) === 2 && $nameval[0] === $name) {
                return $nameval[1];
            }
            if (count($nameval) === 1 && $nameval[0] === $name) {
                return '1';
            }
        }

        return $default;
    }
}