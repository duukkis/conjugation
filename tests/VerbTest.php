<?php
use PHPUnit\Framework\TestCase;

class VerbTest extends TestCase
{
    public function testVerbs()
    {
      include(__DIR__.'/testset.php');
      include(__DIR__.'/../verbicate.php');
      $conjugate = new Verbicate();
      foreach($testSet AS $verb => $corr){
        $this->assertSame($corr["preesens"][0], $conjugate->preesensMe($verb));
        $this->assertSame($corr["preesens"][1], $conjugate->preesensYou($verb));
        $this->assertSame($corr["preesens"][2], $conjugate->preesensSHe($verb));
        $this->assertSame($corr["preesens"][3], $conjugate->preesensPluralWe($verb));
        $this->assertSame($corr["preesens"][4], $conjugate->preesensPluralYou($verb));
        $this->assertSame($corr["preesens"][5], $conjugate->preesensPluralThey($verb));

        $this->assertSame($corr["imperfect"][0], $conjugate->imperfectMe($verb));
        $this->assertSame($corr["imperfect"][1], $conjugate->imperfectYou($verb));
        $this->assertSame($corr["imperfect"][2], $conjugate->imperfectSHe($verb));
        $this->assertSame($corr["imperfect"][3], $conjugate->imperfectPluralWe($verb));
        $this->assertSame($corr["imperfect"][4], $conjugate->imperfectPluralYou($verb));
        $this->assertSame($corr["imperfect"][5], $conjugate->imperfectPluralThey($verb));

        $this->assertSame($corr["perfect"][0], $conjugate->perfectSingle($verb));
        $this->assertSame($corr["perfect"][1], $conjugate->perfectPlural($verb));

        $this->assertSame($corr["imperative"][0], $conjugate->imperativeSingle($verb));
        $this->assertSame($corr["imperative"][1], $conjugate->imperativePlural($verb));
      }
    }
}
