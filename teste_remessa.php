<?php
require __DIR__.'/vendor/autoload.php';

use CnabPHP\Remessa;

$arquivo = new Remessa('Caixa','cnab240_SIGCB',array(
    'nome_empresa' =>"Empresa ABC", // seu nome de empresa
    'tipo_inscricao'  => 2, // 1 para cpf, 2 cnpj
    'numero_inscricao' => '2132122-2121\212121', // seu cpf ou cnpj completo
    'agencia'       => '1234', // agencia sem o digito verificador
    'agencia_dv'    => 1, // somente o digito verificador da agencia
    'conta'         => '12345', // número da conta
    'conta_dac'     => 1, // digito da conta
    'codigo_beneficiario'     => '123456', // codigo fornecido pelo banco
    'numero_sequencial_arquivo'     => 1, // sequencial do arquivo um numero novo para cada arquivo gerado
));
$lote  = $arquivo->addLote(array('tipo_servico'=> 1)); // tipo_servico  = 1 para cobrança registrada, 2 para sem registro

$lote->inserirDetalhe(array(
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
));
echo $arquivo->getText();