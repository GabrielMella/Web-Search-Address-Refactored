<?php

namespace CEP\Model;

use CEP\Service\DataBase;
use PDO;
use Throwable;

class Endereco
{
    private string $_cep;
    private string $_uf;
    private string $_localidade;
    private string $_bairro;
    private string $_logradouro;
    private PDO $conn;

    public function __construct(string $_cep, string $_uf, string $_localidade, string $_bairro, string $_logradouro)
    {
        $this->cep = $_cep;
        $this->uf = $_uf;
        $this->localidade = $_localidade;
        $this->bairro = $_bairro;
        $this->logradouro = $_logradouro;
    }

    public function gravarEnderecoDB(): bool
    {
        $_sucesso = false;

        try {
            // Inserindo as dados no banco
            $_db = new DataBase();
            $this->conn = $_db->retornaConnDB();

            $query = $this->conn->prepare("INSERT INTO dados_cep(cep, rua, bairro, cidade, estado) VALUES(:cepNumber, :rua, :bairro, :cidade, :estado)");
            $query->bindValue( ':cepNumber', $this->cep);
            $query->bindValue( ':rua', $this->logradouro);
            $query->bindValue( ':bairro', $this->bairro);
            $query->bindValue( ':cidade', $this->localidade);
            $query->bindValue( ':estado', $this->uf);
            $query->execute();
            $_sucesso = true;
        }catch(Throwable $_erroOuExcecao)
        {
            error_log($_erroOuExcecao->getMessage(), 3,'errors/log.txt');
        }

        return $_sucesso;
    }


}