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
include("vendor/autoload.php");

use Conjugation\Noun;

$noun = "nalle";
$conj = new Noun();

print $conj->nominative($noun)["answer"].PHP_EOL;
print $conj->genitive($noun)["answer"].PHP_EOL;
print $conj->akkusative($noun)["answer"].PHP_EOL;
print $conj->partitive($noun)["answer"].PHP_EOL;
print $conj->essive($noun)["answer"].PHP_EOL;
print $conj->translative($noun)["answer"].PHP_EOL;
print $conj->inessive($noun)["answer"].PHP_EOL;
print $conj->elative($noun)["answer"].PHP_EOL;
print $conj->illative($noun)["answer"].PHP_EOL;
print $conj->adessive($noun)["answer"].PHP_EOL;
print $conj->ablative($noun)["answer"].PHP_EOL;
print $conj->allative($noun)["answer"].PHP_EOL;
print $conj->abessive($noun)["answer"].PHP_EOL;

$plural = $conj->plural($noun)["answer"];
print $plural.PHP_EOL;
print $conj->nominative($plural)["answer"].PHP_EOL;
print $conj->genitive($plural)["answer"].PHP_EOL;
print $conj->akkusative($plural)["answer"].PHP_EOL;
print $conj->partitive($plural)["answer"].PHP_EOL;
print $conj->essive($plural)["answer"].PHP_EOL;
print $conj->translative($plural)["answer"].PHP_EOL;
print $conj->inessive($plural)["answer"].PHP_EOL;
print $conj->elative($plural)["answer"].PHP_EOL;
print $conj->illative($plural)["answer"].PHP_EOL;
print $conj->adessive($plural)["answer"].PHP_EOL;
print $conj->ablative($plural)["answer"].PHP_EOL;
print $conj->allative($plural)["answer"].PHP_EOL;
print $conj->abessive($plural)["answer"].PHP_EOL;
```

prints out

```
nalle
nallen
nalle
nallea
nallena
nalleksi
nallessa
nallesta
nalleen
nallella
nallelta
nallelle
nalletta
nallet
nallet
nallejen
nallet
nalleja
nalleina
nalleiksi
nalleissa
nalleista
nalleihin
nalleilla
nalleilta
nalleille
nalleitta
```

## Introducing also verbs!
- Present tense, preesens
- Imperfect, imperfekti
- Perfect
- Imperative

```php
// or autolaod
use Conjugation/Verb;

$conjugate = new Verb();
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