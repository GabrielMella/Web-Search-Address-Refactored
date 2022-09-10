<?php


namespace CEP\Service;

use GuzzleHttp\ClientInterface;
use SimpleXMLElement;
use Throwable;


class Requisicao
{
    private string $cepNumber;
    private ClientInterface $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function buscaCep(string $_cepNumber): SimpleXMLElement
    {
        $_ret = false;
        $_url = "https://viacep.com.br/ws/{$_cepNumber}/xml/";

        try
        {
            $_req = $this->httpClient->request('GET', $_url);
            $_ret = simplexml_load_string($_req->getBody()->getContents());

        }catch (Throwable $_erroOuExcecao)
        {
            error_log($_erroOuExcecao->getMessage(), 3,'errors/log.txt');
        }

        return $_ret;
    }

    public function formatarCep(string $_cepnumber): string
    {
        return preg_replace("/[^0-9]/", "", $_cepnumber);
    }
}