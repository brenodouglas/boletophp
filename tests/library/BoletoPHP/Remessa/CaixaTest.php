<?php
namespace BoletoPHP\Remessa;

use BoletoPHP\Remessa\Caixa;
use DateTime;
use BoletoPHP\Types\Aceite;

class CaixaTest extends \PHPUnit_Framework_TestCase
{
    public $remessa;

    public function setUp()
    {
        $this->remessa = new Caixa([
            'nome_empresa' =>"Empresa ABC", // seu nome de empresa
            'tipo_inscricao'  => 2, // 1 para cpf, 2 cnpj
            'numero_inscricao' => '78.064.017/0001-08', // seu cpf ou cnpj completo
            'agencia'       => '1234', // agencia sem o digito verificador
            'agencia_dv'    => 1, // somente o digito verificador da agencia
            'conta'         => '12345', // número da conta
            'conta_dac'     => 1, // digito da conta
            'codigo_beneficiario'     => '123456', // codigo fornecido pelo banco
            'numero_sequencial_arquivo'     => 1, // sequencial do arquivo um numero novo para cada arquivo gerado
            'tipo_servico' => 1
        ]);
    }

    public function tearDown()
    {
        $datetime = new \DateTime();
        @unlink(__DIR__."/".$datetime->format('d-m-Y')."-remessa.ret");
    }

    private function push()
    {
      $this->remessa->push([
          'codigo_ocorrencia' => 1, //1 = Entrada de título, para outras opçoes ver nota explicativa C004 manual Cnab_SIGCB na pasta docs
          'nosso_numero'      => 1, // numero sequencial de boleto
          'seu_numero'        => 1,// se nao informado usarei o nosso numero
          'especie_titulo'    => "DM", // informar dm e sera convertido para codigo em qualquer laytou conferir em especie.php
          'valor'             => 100.00, // Valor do boleto como float valido em php
          'emissao_boleto'        => 2, // tipo de emissao do boleto informar 2 para emissao pelo beneficiario e 1 para emissao pelo banco
          'protestar'        => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias
          'nome_pagador'      => "JOSÉ da SILVA ALVES", // O Pagador é o cliente, preste atenção nos campos abaixo
          'tipo_inscricao'    => 1, //campo fixo, escreva '1' se for pessoa fisica, 2 se for pessoa juridica
          'numero_inscricao'  => '123.122.123-56',//cpf ou ncpj do pagador
          'endereco_pagador'  => 'Rua dos developers,123 sl 103',
          'bairro_pagador'     => 'Bairro da insonia',
          'cep_pagador'        => '12345-123', // com hífem
          'cidade_pagador'     => 'Londrina',
          'uf_pagador'         => 'PR',
          'data_vencimento'    => '2016-04-09', // informar a data neste formato
          'data_emissao'       => '2016-04-09', // informar a data neste formato
          'vlr_juros'          => 0.15, // Valor do juros de 1 dia'
          'data_desconto'      => '2016-04-09', // informar a data neste formato
          'vlr_desconto'       => '0', // Valor do desconto
          'prazo'              => 5, // prazo de dias para o cliente pagar após o vencimento
          'mensagem'           => 'JUROS de R$0,15 ao dia'.PHP_EOL."Não receber apos 30 dias",
          'email_pagador'         => 'rogerio@ciatec.net', // data da multa
          'data_multa'         => '2016-04-09', // informar a data neste formato, // data da multa
          'valor_multa'        => 30.00, // valor da multa
      ]);

    }

    public function testPush()
    {
        $this->push();
        $this->assertCount(1, $this->remessa);
    }

    public function testSave()
    {
        $dateTime = new DateTime();
        $this->push();
        $this->push();
        $this->remessa->setDir(__DIR__.DIRECTORY_SEPARATOR);
        $this->remessa->save();

        $this->assertCount(2, $this->remessa);
        $this->assertFileExists(__DIR__."/".$dateTime->format('d-m-Y')."-remessa.ret");
        foreach($this->remessa as $remessa)
          $this->assertEquals('1', $remessa['nosso_numero']);
    }

    public function testDownloadUri()
    {
        $dateTime = new DateTime();
        $this->push();
        $this->push();
        $this->remessa->save();
        $uri = $this->remessa->downloadUri();

        $this->assertCount(2, $this->remessa);
        $this->assertFileExists($uri);
    }

}
