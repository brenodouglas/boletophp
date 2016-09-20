<?php
namespace BoletoPHP\Remessa;

use BoletoPHP\Model\Remessa;
use CnabPHP\Remessa as RemessaService;

class Caixa implements \Iterator, \Countable
{
    private $dataCedente;
    private $dadosBoletos;
    private $remessa;
    private $lote;
    private $current = 0;
    private $model;
    private $dir = "/tmp/";

    public function __construct(array $dadosDoCedente)
    {
        $this->dataCedente = $dadosDoCedente;
        $this->model = new Remessa();
        $this->remessa =    new RemessaService('104','cnab240_SIGCB', $dadosDoCedente);
        $this->lote = $this->remessa->addLote(['tipo_servico' => $dadosDoCedente['tipo_servico']]);
    }

    public function push(array $dados)
    {
        $this->dadosBoletos[] = $dados;
    }

    public function setDir($dir)
    {
        if(substr($dir, -1) != "/")
            $dir .= "/";

        $this->dir = $dir;
    }

    public function save()
    {
        $dateTime = new \DateTime();

        $this->model->save(json_encode($this->dadosBoletos));
        foreach( $this->dadosBoletos as $boleto)
            $this->lote->inserirDetalhe($boleto);

        $dir = $this->dir.$dateTime->format("d-m-Y")."-remessa.ret";
        return $this->write($dir, $this->remessa->getText());
    }

    protected function write($dir, $text)
    {
        echo $dir.PHP_EOL;
        return file_put_contents($dir, $text);
    }

    public function count()
    {
        return count($this->dadosBoletos);
    }

    public function downloadUri()
    {
        $dateTime = new \DateTime();
        return $this->dir.$dateTime->format("d-m-Y")."-remessa.ret";
    }

  	public function current()
    {
  		return $this->dadosBoletos[$this->current];
  	}

  	public function next()
    {
  		$this->current += 1;
  	}

  	public function rewind()
    {
  		$this->current = 0;
  	}

  	public function key()
    {
  		return $this->current;
  	}

  	public function valid()
    {
  		return isset($this->dadosBoletos[$this->current]);
  	}

}
