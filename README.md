# Conjugation
PHP Conjugation library for Finnish nouns and verbs.
PHP:lla tehty taivutuskirjasto suomen kielen substantiiveille ja verbeille.

## Nouns, substantiivit

- Genetiivi - genitive (-n)
- Translatiivi - translative (-ksi)
- Inessiivi - inessive (-ssa)
- Elatiivi - elative (-sta)
- Adessiivi - adessive (-lla)
- Ablatiivi - ablative (-lta)
- Allatiivi - allative (-lle)
- Abessiivi - abessive (-tta)
- Partitiivi - partitive (-a, ta, tta)
- Essiivi - essive (-na)
- Illatiivi - illative (-an, -en, -han, -seen)
- Akkusatiivi - akkusative, not implemented, same as nominative/genetive, so returns nominative

```php
include('function.php');
$conj = new Conjugate();
$result = $conj->essive("nalle");
print $result["answer"];
```

## Introducing also verbs!
- Present tense, preesens
- Imperfect, imperfekti

```php
include('verbicate.php');
$conjugate = new Verbigate(false);
$me = $conjugate->presentTenseMe("taivuttaa");
print "minä ".$me["answer"];
```

## Noun demo
http://www.palomaki.info/apps/conjugation/

## Tests
PHP Codesniffer rules in ruleset.xml, run with:
```
phpcs --standard=ruleset.xml *.php
```

## Helper material for conjugation

Wordlist from Institute for the Languages of Finland
KOTIMAISTEN KIELTEN KESKUKSEN NYKYSUOMEN SANALISTA

http://kaino.kotus.fi/sanat/nykysuomi/