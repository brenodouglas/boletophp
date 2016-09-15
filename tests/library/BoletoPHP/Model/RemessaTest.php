<?php
namespace BoletoPHP\Model;

use BoletoPHP\Model\Remessa;

class RemessaTest extends \PHPUnit_Framework_TestCase
{
    public function testSave()
    {
        $remessa = new Remessa();
        $this->assertTrue($remessa->save(json_encode(['nome' => 'Fulano sicrano'])));

        $result = $remessa->finadAll();
        $this->assertTrue($result->valid());
    }

    public function testResetDataBase()
    {
        $remessa = new Remessa();
        $remessa->save(json_encode(['nome' => 'Jax Teller']));
        $remessa->save(json_encode(['nome' => 'Mr Robot']));

        $allResult = $remessa->findAll();

        $this->assertTrue($allResult->valid());

        $remessa->resetDataBase();

        $allResult = $remessa->findAll();
        $this->assertFalse($allResult);
    }

    public function testExec()
    {
        
    }

    public function testExecInvalidQuery()
    {

    }
}
