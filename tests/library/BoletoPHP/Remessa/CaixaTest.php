<?php
namespace BoletoPHP\Remessa;

use BoletoPHP\Remessa\Caixa;
use DateTime;

class CaixaTest extends \PHPUnit_Framework_TestCase
{
    public $remessa;

    public function setUp()
    {
        $this->remessa = new Caixa([
          'data_geracao'  => new DateTime(),
          'data_gravacao' => new DateTime(),
          'nome_fantasia' => 'Nome Fantasia da sua empresa',
          'razao_social'  => 'Razão social da sua empresa',
          'cnpj'          => '53.384.362/0001-90',
          'banco'         => \Cnab\Banco::CEF,
          'logradouro'    => 'Av. 2 de janeiro',
          'numero'        => '00',
          'bairro'        => 'Bairro uruara',
          'cidade'        => 'Charming', // aaaah referencias
          'uf'            => 'CA',
          'cep'           => '78000-000',
          'agencia'       => '1111',
          'conta'         => '22222',
          'conta_dac'     => '2',
        ]);
    }

    private function push()
    {
      $this->remessa->push([
          'codigo_ocorrencia' => 1, // 1 = Entrada de título, futuramente poderemos ter uma constante
          'nosso_numero'      => '1234567',
          'numero_documento'  => '1234567',
          'carteira'          => '109',
          'especie'           => \Cnab\Especie::CEF_OUTROS,
          'valor'             => 100.39, // Valor do boleto
          'instrucao1'        => 2, // 1 = Protestar com (Prazo) dias, 2 = Devolver após (Prazo) dias, futuramente poderemos ter uma constante
          'instrucao2'        => 0, // preenchido com zeros
          'sacado_nome'       => 'Sicrano Beltrano Fulano', // O Sacado é o cliente, preste atenção nos campos abaixo
          'sacado_tipo'       => 'cpf', //campo fixo, escreva 'cpf' (sim as letras cpf) se for pessoa fisica, cnpj se for pessoa juridica
          'sacado_cpf'        => '378.324.567-28',
          'sacado_logradouro' => 'Av. feliz',
          'sacado_bairro'     => 'Bairro da Alegria',
          'sacado_cep'        => '78000-0000', // sem hífem
          'sacado_cidade'     => 'Storybrooke',
          'sacado_uf'         => 'AC',
          'data_vencimento'   => new DateTime(),
          'data_cadastro'     => new DateTime(),
          'juros_de_um_dia'     => 0.10, // Valor do juros de 1 dia'
          'data_desconto'       => new DateTime(),
          'valor_desconto'      => 0.0, // Valor do desconto
          'prazo'               => 2, // prazo de dias para o cliente pagar após o vencimento
          'taxa_de_permanencia' => '0', //00 = Acata Comissão por Dia (recomendável), 51 Acata Condições de Cadastramento na CAIXA
          'mensagem'            => 'Descrição do boleto',
          'data_multa'          => new DateTime(), // data da multa
          'valor_multa'         => 0.0, // valor da multa
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
        $this->remessa->save(__DIR__.DIRECTORY_SEPARATOR);

        $this->assertCount(2, $this->remessa);
        $this->assertFileExists(__DIR__."/".$dateTime->format('d-m-Y')."-remessa.txt");
        foreach($this->remessa as $remessa)
          $this->assertEquals('1234567', $remessa['nosso_numero']);
    }

    public function testDownloadUri()
    {
        $dateTime = new DateTime();
        $this->push();
        $this->push();
        $this->remessa->save(__DIR__.DIRECTORY_SEPARATOR);
        $uri = $this->remessa->downloadUri();

        $this->assertCount(2, $this->remessa);
        $this->assertFileExists($uri);
    }

}
