<?php

use Conjugation\Noun;
use PHPUnit\Framework\TestCase;

class NounTest extends TestCase
{
    public function testBasicGenitives()
    {
        $testSet = [
            "säde" => "säteen",
            "tee" => "teen",
            "college" => "collegen",
            "pahe" => "paheen",
            "tie" => "tien",
            "ohje" => "ohjeen",
            "vehje" => "vehkeen",
            "lomake" => "lomakkeen",
            "suihke" => "suihkeen",
            "leike" => "leikkeen",
            "nukke" => "nuken",
            "välke" => "välkkeen",
            "eläke" => "eläkkeen",
            "roikale" => "roikaleen",
            "viipale" => "viipaleen",
            "käärme" => "käärmeen",
            "kuume" => "kuumeen",
            "aine" => "aineen",
            "huone" => "huoneen",
            "ripe" => "rippeen",
            "utare" => "utareen",
            "vire" => "vireen",
            "tuore" => "tuoreen",
            "piirre" => "piirteen",
            "murre" => "murteen",
            "liete" => "lietteen",
            "laite" => "laitteen",
            "peite" => "peitteen",
            "jännite" => "jännitteen",
            "polte" => "poltteen",
            "painiote" => "painiotteen",
            "rokote" => "rokotteen",
            "tuote" => "tuotteen",
            "raaste" => "raasteen",
            "raglette" => "ragleten",
            "palaute" => "palautteen",
            "kuorrute" => "kuorrutteen",
            "saaste" => "saasteen",
            "pakaste" => "pakasteen",
            "neste" => "nesteen",
            "paiste" => "paisteen",
            "sumute" => "sumutteen",
            "puute" => "puutteen",
            "näyte" => "näytteen",
            "jäte" => "jätteen",
            "poikue" => "poikueen",
            "joukkue" => "joukkueen",
            "puolue" => "puolueen",
            "kiertue" => "kiertueen",
            "viive" => "viipeen",
            "tarve" => "tarpeen",
            "terve" => "terveen",
            "turve" => "turpeen",
            "yhtye" => "yhtyeen",
            "säe" => "säkeen",
            "huume" => "huumeen",
            "amme" => "ammeen",
            "nalle" => "nallen",
            "kanne" => "kanteen",
            "puriste" => "puristeen",
            "joule" => "joulen",
            "lude" => "luteen",
            "tilanne" => "tilanteen",
            "koe" => "kokeen",
            "aie" => "aikeen",
            "tae" => "takeen",
            "lakka" => "lakan",
            "käpy" => "kävyn",
            "kippo" => "kipon",
            "matto" => "maton",
            "reki" => "reen",
            "lupa" => "luvan",
            "pato" => "padon",
            "hanki" => "hangen",
            "kampi" => "kammen",
            "pelto" => "pellon",
            "ranta" => "rannan",
            "parta" => "parran",
            "kylki" => "kyljen",
            "arki" => "arjen",
            "sisin" => "sisimen",
            "tosi" => "toden",
            "biisi" => "biisin",
            "lasi" => "lasin",
            "susi" => "suden",
            "käsi" => "käden",
            "vesi" => "veden",
            "aloite" => "aloitteen",
            "pilkahdus" => "pilkahduksen",
            "silakka" => "silakan",
            "tunneli" => "tunnelin",
            "lammas" => "lampaan",
            "tiede" => "tieteen",
            "kudos" => "kudoksen",
            "liikenne" => "liikenteen",
            "tehdas" => "tehtaan",
            "ahdas" => "ahtaan",
            "ohjaus" => "ohjauksen",
            "ohjus" => "ohjuksen",
            "tunti" => "tunnin",
            "panta" => "pannan",
            "kyntö" => "kynnön",
            "lintu" => "linnun",
            "teos" => "teoksen",
            "pöytä" => "pöydän",
            "puku" => "puvun",
            "laku" => "lakun",
            "luku" => "luvun",
            "rakennus" => "rakennuksen",
            "sulatus" => "sulatuksen",
            "vuosi" => "vuoden",
            "suotavuus" => "suotavuuden",
            "väsymys" => "väsymyksen",
            "toppaus" => "toppauksen",
            "suopa" => "suovan",
            "pappa" => "papan",
            "haapa" => "haavan",
            "peräsuolisyöpä" => "peräsuolisyövän",
            "mätä" => "mädän",
            "kide" => "kiteen",
            "kanta" => "kannan",
            "sammal" => "sammalen",
            "toiminta" => "toiminnan",
            "elin" => "elimen",
            "puhallin" => "puhaltimen",
            "kulu" => "kulun",
            "valas" => "valaan",
            "jauhe" => "jauheen",
            "pönttö" => "pöntön",
            "puhe" => "puheen",
            "teroitin" => "teroittimen",
            "rakas" => "rakkaan",
            "sormikas" => "sormikkaan",
            "kaunis" => "kauniin",
            "retki" => "retken",
            "muhennos" => "muhennoksen",
            "onki" => "ongen",
            "tikas" => "tikkaan",
            "henki" => "hengen",
            "pyörre" => "pyörteen",
            "luomi" => "luomen",
            "lihas" => "lihaksen",
            "liike" => "liikkeen",
            "alaston" => "alastoman",
            "ostos" => "ostoksen",
            "kierto" => "kierron",
            "suhde" => "suhteen",
            "lähde" => "lähteen",
            "esikisa" => "esikisan",
            "louhos" => "louhoksen",
            "kone" => "koneen",
            "mekko" => "mekon",
            "kylpy" => "kylvyn",
            "taito" => "taidon",
            "tauti" => "taudin",
            "mestaruus" => "mestaruuden",
            "piste" => "pisteen",
            "kiusaus" => "kiusauksen",
            "runsaus" => "runsauden",
            "epätarkkuus" => "epätarkkuuden",
            "pihti" => "pihdin",
            "kuukausi" => "kuukauden",
            "lehti" => "lehden",
            "kertomus" => "kertomuksen",
            "kasvain" => "kasvaimen",
            "huopa" => "huovan",
            "vanki" => "vangin",
            "oikeus" => "oikeuden",
            "huolto" => "huollon",
            "kaulus" => "kauluksen",
            "alue" => "alueen",
            "kirjallisuus" => "kirjallisuuden",
            "mieli" => "mielen",
            "lanka" => "langan",
            "peräpeili" => "peräpeilin",
            "rampa" => "ramman",
            "valotiheys" => "valotiheyden",
            "tasapeli" => "tasapelin",
            "rinne" => "rinteen",
            "liikerata" => "liikeradan",
            "lähtö" => "lähdön",
            "onnettomuus" => "onnettomuuden",
            "määräys" => "määräyksen",
            "sumutin" => "sumuttimen",
            "tanssit" => "tanssien",
            "omenakota" => "omenakodan",
            "hedelmä" => "hedelmän",
            "kupu" => "kuvun",
            "talous" => "talouden",
            "koti" => "kodin",
            "rakkaus" => "rakkauden",
            "ääni" => "äänen",
            "liikenneneuvos" => "liikenneneuvoksen",
            "olo" => "olon",
            "herttuatar" => "herttuattaren",
            "kyky" => "kyvyn",
            "tuuli" => "tuulen",
            "kuponki" => "kupongin",
            "toimi" => "toimen",
            "sairaus" => "sairauden",
            "vierus" => "vieruksen",
            "opas" => "oppaan",
            "piiras" => "piiraan",
            "kiihtyvyys" => "kiihtyvyyden",
            "kokous" => "kokouksen",
            "kuitu" => "kuidun",
            "poika" => "pojan",
            "barbaari" => "barbaarin",
            "ommel" => "ompeleen",
            "johto" => "johdon",
            "kohtu" => "kohdun",
            "kivi" => "kiven",
            "joukko" => "joukon",
            "silta" => "sillan",
            "purkaus" => "purkauksen",
            "raikas" => "raikkaan",
            "teko" => "teon",
            "lampi" => "lammen",
            "kumpi" => "kumman",
            "rako" => "raon",
            "rikos" => "rikoksen",
            "köysi" => "köyden",
            "bisnes" => "bisneksen",
            "asia" => "asian",
            "siemen" => "siemenen",
            "stadion" => "stadionin",
            "lapsi" => "lapsen",
            "porsas" => "porsaan",
            "vuotias" => "vuotiaan",
            "pylväs" => "pylvään",
            "kansi" => "kannen",
            "veitsi" => "veitsen",
            "aika" => "ajan",
            "sieni" => "sienen",
            "olosuhde" => "olosuhteen",
            "yhteys" => "yhteyden",
            "päälys" => "päälyksen",
            "lääke" => "lääkkeen",
            "naulittu" => "naulitun",
            "metsä" => "metsän",
            "kärsäkäs" => "kärsäkkään",
            "näkö" => "näön",
            "juoni" => "juonen",
            "taklaus" => "taklauksen",
            "löytö" => "löydön",
            "ranneote" => "ranneotteen",
            "kvartsi" => "kvartsin",
            "vaunu" => "vaunun",
            "vaate" => "vaatteen",
            "vapaus" => "vapauden",
            "pöksyt" => "pöksyjen",
            "uni" => "unen",
            "kota" => "kodan",
            "virtaus" => "virtauksen",
            "tulos" => "tuloksen",
            "markkina" => "markkinan",
            "teräs" => "teräksen",
            "vaaka" => "vaa'an",
            "energia" => "energian",
            "kerääjä" => "kerääjän",
            "lämmitys" => "lämmityksen",
            "koru" => "korun",
            "kukka" => "kukan",
            "väki" => "väen",
            "viikko" => "viikon",
            "kyljys" => "kyljyksen",
            "enemmistö" => "enemmistön",
            "kori" => "korin",
            "lehdistö" => "lehdistön",
            "pommi" => "pommin",
            "turismi" => "turismin",
            "leikkaus" => "leikkauksen",
            "tarjous" => "tarjouksen",
            "hinta" => "hinnan",
            "kunta" => "kunnan",
            "apila" => "apilan",
            "istuin" => "istuimen",
            "betoni" => "betonin",
            "kolari" => "kolarin",
            "usko" => "uskon",
            "rauhanen" => "rauhasen",
            "rotu" => "rodun",
            "kätkö" => "kätkön",
            "summeri" => "summerin",
            "pieni" => "pienen",
            "kilpi" => "kilven",
            "näyttelyvieras" => "näyttelyvieraan",
            "tekniikka" => "tekniikan",
            "ovi" => "oven",
            "alusta" => "alustan",
            "kortisoni" => "kortisonin",
            "riita" => "riidan",
            "mitta" => "mitan",
            "seutu" => "seudun",
            "yhdistetty" => "yhdistetyn",
            "sähkö" => "sähkön",
            "käytäväpolitiikka" => "käytäväpolitiikan",
            "säkkituoli" => "säkkituolin",
            "torni" => "tornin",
            "leikki" => "leikin",
            "kypsennys" => "kypsennyksen",
            "muovi" => "muovin",
            "pakkaus" => "pakkauksen",
            "doping" => "dopingin",
            "raskas" => "raskaan",
            "myrkky" => "myrkyn",
            "tähti" => "tähden",
            "lyhty" => "lyhdyn",
            "suihku" => "suihkun",
            "insuliini" => "insuliinin",
            "hyppy" => "hypyn",
            "kinkku" => "kinkun",
            "kieli" => "kielen",
            "ranskan kieli" => "ranskan kielen",
            "keskus" => "keskuksen",
            "pysähdys" => "pysähdyksen",
            "bakteeri" => "bakteerin",
            "ananas" => "ananaksen",
            "purukumi" => "purukumin",
            "tyyppi" => "tyypin",
            "komitea" => "komitean",
            "härkä" => "härän",
            "sarja" => "sarjan",
            "lainoppi" => "lainopin",
            "pouta" => "poudan",
            "tulppaani" => "tulppaanin",
            "purje" => "purjeen",
            "peluri" => "pelurin",
            "tieto" => "tiedon",
            "myrsky" => "myrskyn",
            "muoto" => "muodon",
            "raskaus" => "raskauden",
            "selkä" => "selän",
            "kaksisataa" => "kahdensadan",
            "halko" => "halon",
            "naamari" => "naamarin",
            "substantiivi" => "substantiivin",
            "viski" => "viskin",
            "ruisku" => "ruiskun",
            "ase" => "aseen",
            "sotilas" => "sotilaan",
            "koneisto" => "koneiston",
            "leipä" => "leivän",
            "karmi" => "karmin",
            "povinen" => "povisen",
            "verhoilu" => "verhoilun",
            "lupaus" => "lupauksen",
            "terrieri" => "terrierin",
            "muuri" => "muurin",
            "tuuri" => "tuurin",
            "loosi" => "loosin",
            "filmi" => "filmin",
            "mittari" => "mittarin",
            "järvi" => "järven",
            "medaljonki" => "medaljongin",
            "nivel" => "nivelen",
            "seksi" => "seksin",
            "outo" => "oudon",
            "tapaus" => "tapauksen",
            "kestit" => "kestien",
        ];

        // initialize and use cache
        $noun = new Noun(true);

        foreach ($testSet as $word => $correct_answer) {
            $actual = $noun->genitive($word);
            $this->assertEquals($correct_answer, $actual["answer"]);
            $this->assertEquals($correct_answer, $noun->newGenetive($word, "n"));
        }

        // create set for all
        if (false) {
            file_put_contents("result.txt", "");
            foreach ($testSet as $word => $correct_answer) {
                $result = "";
                $plural = $noun->plural($word)["answer"];
                $result .= $noun->nominative($word)["answer"].";";
                $result .= $noun->genitive($word)["answer"].";";
                $result .= $noun->akkusative($word)["answer"].";";
                $result .= $noun->partitive($word)["answer"].";";
                $result .= $noun->essive($word)["answer"].";";
                $result .= $noun->translative($word)["answer"].";";
                $result .= $noun->inessive($word)["answer"].";";
                $result .= $noun->elative($word)["answer"].";";
                $result .= $noun->illative($word)["answer"].";";
                $result .= $noun->adessive($word)["answer"].";";
                $result .= $noun->ablative($word)["answer"].";";
                $result .= $noun->allative($word)["answer"].";";
                $result .= $noun->abessive($word)["answer"].";";
                $result .= $plural.";";
                $word = $plural;
                $result .= $noun->genitive($word)["answer"].";";
                $result .= $noun->akkusative($word)["answer"].";";
                $result .= $noun->partitive($word)["answer"].";";
                $result .= $noun->essive($word)["answer"].";";
                $result .= $noun->translative($word)["answer"].";";
                $result .= $noun->inessive($word)["answer"].";";
                $result .= $noun->elative($word)["answer"].";";
                $result .= $noun->illative($word)["answer"].";";
                $result .= $noun->adessive($word)["answer"].";";
                $result .= $noun->ablative($word)["answer"].";";
                $result .= $noun->allative($word)["answer"].";";
                $result .= $noun->abessive($word)["answer"].PHP_EOL;
                file_put_contents("result.txt", $result, FILE_APPEND);
            }
        }

        if (false) {
            foreach ($testSet as $word => $correct_answer) {
                $noun->detectWordType($word, "");
            }
        }
    }

    public function testPresetWords(): void
    {
        // initialize and use cache
        $noun = new Noun(false);
        $words = [
            "aakkonen", "aalto", "aari", "aarteisto", "ahdas", "ahven", "aie", "aika", "aine", "ainoa", "airut",
            "aitta", "aivoitus", "aivot", "akne", "alanko", "alaston", "alkeet", "alkovi", "alku", "allas", "alpi",
            "altis", "alue", "alusta", "amme", "analogia", "ananas", "antelias", "anto", "apaja", "apila", "appi",
            "apu", "arki", "arpi", "arvelu", "ase", "asema", "askel", "auer", "autio", "auto", "autuas", "avanto",
            "bakteeri", "balladi", "banaali", "banaani", "band", "bebe", "betoni", "biografi", "bisnes", "college",
            "desi", "doping", "edam", "elin", "eläke", "emäntä", "enemmistö", "enne", "fan", "farc", "filmi", "gnu",
            "golf", "haahti", "haka", "haku", "halpa", "hame", "hammas", "hanhi", "hanka", "hanki", "hapan", "happi",
            "hapsi", "harras", "harteet", "hattu", "hauis", "hauki", "heikkous", "heisi", "helle", "helmi", "helpi",
            "henki", "herttua", "herttuatar", "hetki", "hevonen", "hiiri", "hiisi", "hiki", "hinku", "hirsi", "hirvas",
            "hius", "hoikka", "hollanti", "honka", "hontelo", "hoppu", "housut", "hulluus", "huokoisuus", "huoli",
            "huopa", "hupi", "huvi", "hylky", "hymni", "hyppy", "hän", "härkä", "ien", "ies", "iiris", "ikä",
            "illuusio", "impi", "insuliini", "istuin", "iäkäs", "jakso", "jalas", "jaotus", "joka", "joki", "joukko",
            "joule", "jousi", "juhta", "julkkis", "jumala", "juoni", "juossut", "juuri", "jälki", "jälsi", "järki",
            "järvi", "jäsen", "kaadin", "kaari", "kaarto", "kaavin", "kahdeksas", "kaihi", "kaikki", "kaipaus",
            "kajakki", "kala", "kalleus", "kalsium", "kamari", "kampa", "kangas", "kanki", "kannel", "kansi", "kanta",
            "kantaja", "kappa", "karahka", "karmi", "kaski", "kasvain", "kasvi", "katsaus", "katseltu", "katve",
            "kauha", "kaulus", "kaunis", "kauris", "kausi", "kebab", "kenkä", "kenttä", "keppi", "kerroin", "kertomus",
            "kesi", "keskus", "ketju", "kevät", "keväämmällä", "kieli", "kierre", "kiharrin", "kiinteistö", "kiiru",
            "kiiski", "killinki", "kilpi", "kimpi", "kinkku", "kinnas", "kinner", "kippo", "kirves", "kisa", "kiuas",
            "kiusaus", "kives", "kivi", "koe", "kohtu", "koipi", "koira", "koiras", "kokous", "kompa", "koneisto",
            "konsuli", "konvehti", "kori", "korkea", "korpi", "kortisoni", "koru", "koski", "kota", "koti", "kuitu",
            "kukka", "kulkija", "kulta", "kumi", "kumpi", "kumppani", "kumppanuus", "kumpu", "kuollut", "kuori",
            "kuponki", "kuppi", "kurki", "kusi", "kutsu", "kuu", "kuuri", "kuusi", "kuvaus", "kvartsi", "kyky",
            "kyljys", "kylki", "kylpy", "kymi", "kypsennys", "käki", "kärhi", "kärki", "käsi", "käsittely", "kätkö",
            "käärme", "laatu", "lahjakkuus", "lahje", "lahti", "laidun", "laki", "laku", "lampi", "lape", "lapsi",
            "lasi", "laupias", "leah", "lehti", "leikkaus", "leikki", "leikkuu", "leipä", "leiri", "lempi", "lepo",
            "leski", "letku", "leuto", "liesi", "liete", "lihas", "lihava", "liika", "liitto", "lintu", "loitolla",
            "loosi", "lounas", "lujuus", "luku", "lumi", "lumme", "lupa", "lupaus", "lyhty", "lämmin", "lämmitys",
            "länget", "läpi", "löytö", "maa", "maailma", "maar", "maineikas", "makkara", "maku", "marinadi", "markkina",
            "matala", "matka", "me", "medaljonki", "mehu", "mesi", "mies", "minä", "mopo", "morsian", "muki", "muoto",
            "muovi", "mutteri", "muuan", "myrkky", "myrsky", "myyjä", "mäki", "määräys", "naamari", "nahka", "nainen",
            "nalle", "napa", "naru", "neiti", "neitsyt", "niemi", "niini", "nimi", "nivel", "noki", "nugaa", "nukke",
            "numero", "nummi", "nuolaistu", "nuoli", "nuori", "nurmi", "närhi", "näyte", "ohjaus", "ohje", "ohjus",
            "oja", "olki", "oltu", "ommel", "onki", "onneton", "onnettomuus", "onni", "opel", "oppi", "ori", "osa",
            "osoite", "ovi", "paasi", "pahe", "pahin", "pakkaus", "palaute", "palle", "pano", "paperi", "paras", "parfait",
            "parta", "pasuuna", "pata", "pato", "peili", "peitsi", "peli", "pelti", "peluri", "penger", "peruna",
            "peti", "pieni", "piennar", "pieru", "pihatto", "pihdit", "pihti", "pii", "piinaus", "piki", "pilkahdus",
            "piru", "pistooli", "pitkä", "pohja", "poika", "poliisi", "poljin", "polte", "pommi", "pop", "porsas",
            "poski", "pouta", "puhuttu", "puin", "punainen", "punk", "punonut", "puoleen", "puoli", "purje", "purkaus",
            "purrut", "purtu", "puu", "pylväs", "pyramidi", "pysähdys", "päitset", "päivä", "päälys", "pöksy", "pöytä",
            "raaka", "raamattu", "raaste", "raglette", "raitis", "rakas", "rakennus", "rakkaus", "rako", "raskas",
            "raskaus", "rastas", "ratas", "rauhanen", "rauta", "reaktori", "reikä", "reisi", "reki", "renki", "revyy",
            "riita", "rimpi", "ripsi", "risti", "rock", "rose", "rotu", "ruis", "ruiske", "ruisku", "runsaus", "ruoka",
            "ruoko", "ruotsi", "rupi", "ränni", "saapas", "saari", "saarni", "saatana", "sade", "saksi", "salaatti",
            "salmi", "sammal", "sampi", "sampo", "sankari", "sappi", "sataa", "savi", "savotta", "seimi", "seksi",
            "selkä", "seutu", "siemen", "sieni", "siipi", "siisti", "sika", "sinappi", "sini", "sinä", "sipuli", "sisar",
            "sisaruus", "sisin", "sivellin", "sivumpana", "solakka", "solki", "soppi", "sormi", "sotilas", "spray",
            "stadion", "substantiivi", "suihke", "suihku", "suikale", "suitset", "suksi", "summeri", "suo", "suodatin",
            "suoli", "suomi", "suoni", "suunta", "suurempi", "suuri", "sydän", "sylki", "symboli", "synty", "säen",
            "sähkö", "sänki", "sänky", "särki", "sääksi", "sääri", "sääski", "tae", "taika", "taival", "taive", "takki",
            "taklaus", "talous", "tammi", "tanssit", "tapaus", "tarjous", "tarve", "tarvike", "tatti", "taulu", "tauti",
            "teak", "tee", "teeri", "teko", "telefoni", "telki", "tennis", "terrieri", "terve", "teräs", "tie",
            "tiedote", "tienoo", "tilavuus", "tilhi", "tiu", "tiuku", "tolppa", "topografia", "topografinen",
            "toppaus", "torni", "tosi", "totuus", "toveri", "trikoo", "tuhat", "tuki", "tulppaani", "tunneli",
            "tunti", "tuoli", "tuomari", "tuomi", "tuoni", "tuppi", "turismi", "turta", "tuska", "tuuli", "typpi",
            "tytär", "tyvi", "tyyni", "tyyppi", "tähti", "täysi", "udar", "uistin", "uksi", "ulappa", "umpi", "uni",
            "uros", "urut", "usko", "utare", "utelias", "uuni", "uusi", "vahti", "vaikutelma", "vaimennin", "valas",
            "valmis", "valo", "valta", "vanki", "vapaa", "vapaus", "vara", "varas", "varvas", "vasen", "vaski", "vastaus",
            "vati", "veitsi", "vekkuli", "veli", "veranta", "veri", "vesi", "vetävyys", "video", "vieras", "vieri",
            "vierus", "vihanta", "viiksi", "viini", "viive", "vika", "vire", "virpi", "viski", "voima", "vuoksi",
            "vuori", "vuosi", "vyyhti", "väki", "väsymys", "yhdistetty", "yhteys", "yhtye", "ylkä", "yö", "äiti",
            "ääni", "öljy"
        ];
        foreach ($words as $word) {
            $actual = $noun->genitive($word);
            $correct_answer = $actual["answer"];
            $this->assertEquals($correct_answer, $noun->newGenetive($word, "n"));
        }
    }
}
