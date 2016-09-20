<?php
namespace BoletoPHP\Model;

use BoletoPHP\Model\Remessa;

class RemessaTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $remessa = new Remessa();
        $this->assertTrue($remessa->save(json_encode(['nome' => 'Fulano sicrano'])));

        $result = $remessa->findAll();
        $this->assertTrue($result->valid());
    }

    public function testExec()
    {
        $remessa = new Remessa();
        $this->assertTrue($remessa->exec("SELECT * FROM retorno"));
    }

    public function testExecInvalidQuery()
    {
        $remessa = new Remessa();
        $this->assertFalse($remessa->exec("SELECT * FROM aa"));
    }
}
