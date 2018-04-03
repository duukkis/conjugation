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
        $this->assertSame($corr["pre"][0], $conjugate->preesensMe($verb));
        $this->assertSame($corr["pre"][1], $conjugate->preesensYou($verb));
        $this->assertSame($corr["pre"][2], $conjugate->preesensSHe($verb));
        $this->assertSame($corr["pre"][3], $conjugate->preesensPluralWe($verb));
        $this->assertSame($corr["pre"][4], $conjugate->preesensPluralYou($verb));
        $this->assertSame($corr["pre"][5], $conjugate->preesensPluralThey($verb));

        $this->assertSame($corr["imp"][0], $conjugate->imperfectMe($verb));
        $this->assertSame($corr["imp"][1], $conjugate->imperfectYou($verb));
        $this->assertSame($corr["imp"][2], $conjugate->imperfectSHe($verb));
        $this->assertSame($corr["imp"][3], $conjugate->imperfectPluralWe($verb));
        $this->assertSame($corr["imp"][4], $conjugate->imperfectPluralYou($verb));
        $this->assertSame($corr["imp"][5], $conjugate->imperfectPluralThey($verb));
      }
    }
}
