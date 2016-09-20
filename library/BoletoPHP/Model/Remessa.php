<?php
namespace BoletoPHP\Model;

/**
* Model para gerenciar dados de remessa, caso o usuario queira um log das remessas geradas
* e queira gerar apenas uma unica vez a cada tempo
*/
class Remessa
{
    /**
     * @var db SQLite3
     */
    private $db;

    public function __construct()
    {
        $this->db = new \SQLite3(__DIR__."/_temp/remessa");
        $this->createTable();
    }

    /**
     * @param String $line
     * @return bool
     */
    public function save($line)
    {
        return $this->db->exec("INSERT INTO retorno(line) VALUES('${line}')");
    }

    /**
     * @return Generator
     */
    public function findAll()
    {
        $results = $this->db->query('SELECT * FROM retorno');
        while ($row = $results->fetchArray())
            yield $row;
    }

    /**
     * @param int id
     * @return array
     */
    public function find($id)
    {
        return $this->db->querySingle('SELECT * FROM retorno');
    }

    /**
     * Apaga e recria banco de dados
     * @return bool
     */
    public function resetDataBase()
    {
        $this->dropTable();
        return $this->createTable();
    }

    /**
     * Executa qualquer comando sql passado
     * @param string $sql
     * @return bool
     */
    public function exec($sql)
    {
        try {
            $res = $this->db->exec($sql);

            if(! $res)
                return false;

            return $res;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Cria tabela em banco de dados
     * @return bool
     */
    private function createTable()
    {
      return $this->db->exec("
          CREATE TABLE IF NOT EXISTS retorno (
              id int auto_increment PRIMARY KEY,
              line text
          )
      ");
    }

    /**
     * Deleta tabela em banco de dados
     * @return bool
     */
    private function dropTable()
    {
        return $this->db->exec("DROP TABLE retorno");
    }
}
