<?php

namespace App\HTTP\Middlewares;

Class Queue{

private static $map=[];

//Fila de middlewares padrao
private static $default=[];

/**Fila de middlewares a serem executados */
private $middlewares = [];

/** Função do controlador */
private $controller;

/** Argumentos da função do controlador */
private $controllerArgs = [];

/* Metodo responsavel por inicializar a classe */
public function __construct($middlewares, $controller, $controllerArgs)
{
    $this->middlewares = array_merge(self::$default, $middlewares);
    $this->middlewares = $middlewares;
    $this->controller = $controller;
    $this->controllerArgs = $controllerArgs;
}

/* Metodo responsavel por definir o mapa de middlewares */
    public static function setMap($map){
        self::$map = $map;
    }

/* Metodo responsavel por definir a fila de middlewares padrao */
public static function setDefault($default){
    self::$default = $default;
}


/* Metodo responsavel por executar a proxima funcao da fila de middlewares */
public function next($request){
// Verificar se ainda tem middlewares na fila
if(count($this->middlewares) == 0){
    return call_user_func_array($this->controller, $this->controllerArgs);
}
//Middlewares
$middleware = array_shift($this->middlewares);

//Veeriffica o mapeamento do middleware
if(!isset(self::$map[$middleware])){
    throw new \Exception("O middleware {$middleware} não foi mapeado.", 500);
}
//Next do middleware
$queue=$this;
$next = function($request) use ($queue){
    return $queue->next($request);
};
//Executar o middleware
 return (new self::$map[$middleware])->handle($request, $next);
}

}
