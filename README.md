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
print $conj->nominative("nalle")["answer"].PHP_EOL;
print $conj->essive("nalle")["answer"].PHP_EOL;
print $conj->essive("nalle")["answer"].PHP_EOL;
print $conj->essive("nalle")["answer"].PHP_EOL;
print $conj->essive("nalle")["answer"].PHP_EOL;
```

## Introducing also verbs!
- Present tense, preesens
- Imperfect, imperfekti
- Perfect
- Imperative

```php
include('verbicate.php');
$conjugate = new Verbicate();
$me = $conjugate->preesensMe("taivuttaa");
print "Minä ".$me.PHP_EOL;
print "Sinä ".$conjugate->preesensYou("taivuttaa").PHP_EOL;
print "Hän ".$conjugate->preesensSHe("taivuttaa").PHP_EOL;
print "Me ".$conjugate->preesensPluralWe("taivuttaa").PHP_EOL;
print "Te ".$conjugate->preesensPluralYou("taivuttaa").PHP_EOL;
print "He ".$conjugate->preesensPluralThey("taivuttaa").PHP_EOL;

print "Minä ".$conjugate->imperfectMe("taivuttaa").PHP_EOL;
print "Sinä ".$conjugate->imperfectYou("taivuttaa").PHP_EOL;
print "Hän ".$conjugate->imperfectSHe("taivuttaa").PHP_EOL;
print "Me ".$conjugate->imperfectPluralWe("taivuttaa").PHP_EOL;
print "Te ".$conjugate->imperfectPluralYou("taivuttaa").PHP_EOL;
print "He ".$conjugate->imperfectPluralThey("taivuttaa").PHP_EOL;

print "Minä olen ".$conjugate->perfectSingle("taivuttaa").PHP_EOL;
print "Te olette ".$conjugate->perfectPlural("taivuttaa").PHP_EOL;

print ucfirst($conjugate->imperativeSingle("taivuttaa")) . "!".PHP_EOL;
print ucfirst($conjugate->imperativePlural("taivuttaa")) . "!".PHP_EOL;
```

Prints out

```
Minä taivutan
Sinä taivutat
Hän taivuttaa
Me taivutamme
Te taivutatte
He taivuttavat
Minä taivutin
Sinä taivutit
Hän taivutti
Me taivutimme
Te taivutitte
He taivuttivat
Minä olen taivuttanut
Te olette taivuttaneet
Taivuta!
Taivuttakaa!
```

## Noun demo
http://www.palomaki.info/apps/conjugation/

## Tests
PHP Codesniffer rules in ruleset.xml, run with:
```
phpcs --standard=tests/ruleset.xml *.php
```
PHPUnit
```
wget -O phpunit https://phar.phpunit.de/phpunit-7.phar
chmod +x phpunit
./phpunit tests/
```

## Helper material for conjugation

Wordlist from Institute for the Languages of Finland
KOTIMAISTEN KIELTEN KESKUKSEN NYKYSUOMEN SANALISTA

http://kaino.kotus.fi/sanat/nykysuomi/