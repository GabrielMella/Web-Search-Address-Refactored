<?php

namespace CEP\Service;

use PDO;

class DataBase
{
    private PDO $conn;

    public function __construct()
    {
        $conn = new PDO('pgsql:host=localhost;dbname=buscacep', 'postgres', 'cadeiras14');
        $this->conn = $conn;
    }

    public function retornaConnDB()
    {
        return $this->conn;
    }

    public function getDadosExistentes($_cepnumber): array
    {
        $_cepnumber = preg_replace("/[^0-9]/", "", $_cepnumber);

        $query = $this->conn->prepare("SELECT * FROM dados_cep WHERE cep=:cepNumber");
        $query->bindParam(':cepNumber', $_cepnumber);
        $query->execute();

        $dados = $query->fetchAll();
        return $dados;
    }
}