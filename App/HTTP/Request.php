<?php

namespace App\HTTP;

class Request
{
    /*** Instancia da classe Router */
    private $router;
    /*** Metodo HTTP da requisição */
    private $httpMethod;

    /*** Indentificador dos recursos da Pagina*/
    private $uri;

    /**Parametros da URL ($_GET)*/
    private $queryParams = [];

    /*** variaveis recebidas no POST da Pagina ($_POST) */
    private $postVars = [];

    /*** Cabeçalho da requisição */
    private $headers = [];

    public function __construct($router){
    $this->router = $router;
    $this->queryParams = $_GET ?? []; 
    $this->postVars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';
    $this->setUri();
    }

    private function setUri(){
        // Separar a URI do GET
        $xUri = explode('?',$this->uri);
        // Define a URI sem GET
        $this->uri = $xUri[0];
    }

    /*** Metodo para obter a instancia de Router */
    public function getRouter()
    {
        return $this->router;
    }
    /****Metodo para obter o método HTTP da requisição */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }

    /** Metodo para obter o URI da requisição */
    public function getUri()
    {
        return $this->uri;
    }

    /*** Metodo para obter os cabeçalhos da requisição */
    public function getHeaders(){
        return $this->headers;
    }

    /*** Metodo para obter os parâmetros da URL */
    public function getQueryParams()
    {
        return $this->queryParams;
    }

    /*** Metodo para obter as variáveis do POST */
    public function getPostVars()
    {
        return $this->postVars;
    }
}
