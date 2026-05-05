<?php

namespace App\HTTP;

class Response
{
    /*** Código HTTP da resposta  */
    private $httpCode = 200;

    /** cabeçalhos da resposta */
    private $headers = [];

    /***Tipo Conteúdo que esta sendo retornado */
    private $contentType = 'text/html';

    /*** Conteúdo da response */
    private $content;

    /**Metodo responsavel por inicializar a classe e definir os valores */
    public function __construct($httpCode, $content, $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->contentType = $contentType;  
    }

    /*** Metodo responsavel por alterar o tipo de conteúdo */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }   

    /*** Metodo responsavel por adicionar um registro no cabeçalho à response */
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }  
    
    /*** Metodo responsavel por enviar os headers para o navegador */
    private function sendHeaders()
    {
        $this->sendHeaders();
        //Status
        http_response_code($this->httpCode);
        //Enviar Headers
        foreach($this->headers as $key => $value){
            header($key . ': ' . $value);
        }
    }

    /** Metodo responsavel por enviar os cabeçalhos e o conteúdo da resposta para o cliente */
    public function sendResponse()
    {
        switch($this->contentType){
            case 'text/html':
            echo $this->content;
            exit;
        }
    }
}
?>