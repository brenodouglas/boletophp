<?php
namespace BoletoPHP\Remessa;

use BoletoPHP\Model\Remessa;
use Cnab\Banco;
use Cnab\Remessa\Cnab240\Arquivo;

class Caixa implements \Iterator, \Countable
{
    private $dataCedente;
    private $dadosBoletos;
    private $remessa;
    private $current = 0;
    private $model;
    private $dir = "/tmp/";

    public function __construct(array $dadosDoCedente)
    {
        $this->dataCedente = $dadosDoCedente;
        $this->model = new Remessa();
        $this->remessa =  new Arquivo(Banco::CAIXA);
        $this->remessa->configure($dadosDoCedente);
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
        foreach( $this->dadosBoleto as $boleto)
            $this->remessa->insertDetalhe($boleto);

        $dir = $this->dir.$dateTime->format("d-m-Y")."-remessa.txt";
        $this->remessa->save($dir);
        return $dir;
    }

    public function count()
    {
        return count($this->dadosBoletos);
    }

    public function downloadUri()
    {
        $dateTime = new \DateTime();
        retun $dir = $this->dir.$dateTime->format("d-m-Y")."-remessa.txt";
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
