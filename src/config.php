<?php

require 'vendor/autoload.php';

use CEP\Model\Endereco;
use CEP\Service\DataBase;
use CEP\Service\Requisicao;
use GuzzleHttp\Client;


$db = new DataBase();
$_httpCliente = new Client();
$_requisicao = new Requisicao($_httpCliente);

/**
 * Pegando cep do formulário e formatando.
 */
$_cepnumber = $_requisicao->formatarCep( filter_input(INPUT_POST,'cep', FILTER_SANITIZE_SPECIAL_CHARS) );

if(empty($_cepnumber))
{
    error_log('Cep inválido!', 3,'errors/log.txt');
    header('Location: errors/error.php');
}

$_dadosDB = $db->getDadosExistentes($_cepnumber);

if(empty($_dadosDB) )
{
    $_retornoReq = $_requisicao->buscaCep($_cepnumber);

    if($_retornoReq->cep)
    {
        $_retornoReq->cep = $_requisicao->formatarCep($_retornoReq->cep);

        $_endereco = new Endereco(
            $_retornoReq->cep,
            $_retornoReq->uf,
            $_retornoReq->localidade,
            $_retornoReq->bairro,
            $_retornoReq->logradouro,
        );
        $_endereco->gravarEnderecoDB();

        $_resultado = $db->getDadosExistentes($_cepnumber);

        foreach($_resultado as $array){
            $result = $array;
        }
    }
}else {
    foreach($_dadosDB as $array){
        $result = $array;
    }
}

