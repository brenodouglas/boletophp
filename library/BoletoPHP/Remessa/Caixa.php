<?php
namespace BoletoPHP\Remessa;

use BoletoPHP\Model\Remessa;

class Caixa implements \Iterator, \Countable
{
    private $dataCedente;
    private $dadosBoletos;
    private $current = 0;
    private $model;

    public function __construct(array $dadosDoCedente)
    {
        $this->dataCedente = $dadosDoCedente;
        $this->model = new Remessa();
    }

    public function push(array $dados)
    {
        $this->dadosBoletos[] = $dados;
    }

    public function save($dir = "/tmp")
    {
        $this->model->save(json_encode($this->dadosBoletos));
    }

    public function count()
    {
        return count($this->dadosBoletos);
    }

    public function downloadUri()
    {

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
