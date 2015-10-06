<?php



namespace Sdz\BlogBundle\Tests\Utility;
use Sdz\BlogBundle\Utility\Calculator;


class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $calc = new Calculator();
        $result = $calc->add(30, 12);
// vérifie que votre classe a correctement calculé!
        $this->assertEquals(42, $result);
    }
}


