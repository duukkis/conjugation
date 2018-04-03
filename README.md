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
$conjugate = new Verbicate();
$me = $conjugate->preesensMe("taivuttaa");
print "minä ".$me;
print "sinä ".$conjugate->preesensYou("taivuttaa");
print "hän ".$conjugate->preesensSHe("taivuttaa");
print "me ".$conjugate->preesensPluralWe("taivuttaa");
print "te ".$conjugate->preesensPluralYou("taivuttaa");
print "he ".$conjugate->preesensPluralThey("taivuttaa");

print "minä ".$conjugate->imperfectMe("taivuttaa");
print "sinä ".$conjugate->imperfectYou("taivuttaa");
print "hän ".$conjugate->imperfectSHe("taivuttaa");
print "me ".$conjugate->imperfectPluralWe("taivuttaa");
print "te ".$conjugate->imperfectPluralYou("taivuttaa");
print "he ".$conjugate->imperfectPluralThey("taivuttaa");
```

## Noun demo
http://www.palomaki.info/apps/conjugation/

## Tests
PHP Codesniffer rules in ruleset.xml, run with:
```
phpcs --standard=ruleset.xml *.php
```
PHPUnit
````
wget -O phpunit https://phar.phpunit.de/phpunit-5.phar
chmod +x phpunit
./phpunit tests/VerbTest.php
```

## Helper material for conjugation

Wordlist from Institute for the Languages of Finland
KOTIMAISTEN KIELTEN KESKUKSEN NYKYSUOMEN SANALISTA

http://kaino.kotus.fi/sanat/nykysuomi/