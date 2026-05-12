<?php

namespace App\HTTP;

use Closure;
use Exception;
use ReflectionFunction;
use App\HTTP\Middlewares\Queue;

class Router {

/** URL completa do projeto raiz */
private $url = '';

/** Prefixo de todas as rotas */
private $prefix = '';

/** Indices das rotas */
private $routes = [];

/** Instancia de Request */
private $request;

/** Metodo responsavel por inicializar a classe */
public function __construct($url)
{
    $this->request = new Request($this);
    $this->url = $url;
    $this->setPrefix();
}

/** Metodo responsavel por definir o prefixo das rotas */
private function setPrefix()
{
    // Informacoes da URL atual
    $parseUrl = parse_url($this->url);

    // Definir o prefixo
    $this->prefix = $parseUrl['path'] ?? '';
}

/** Metodo responsavel por adicionar uma rota na classe */
private function addRoute($method, $route, $params = [])
{
    // Validacao dos parametros
    foreach($params as $key => $value){
        if($value instanceof Closure){
            $params['controller'] = $value;
            unset($params[$key]);
            continue;
        }
    }

    // Middlewares da rota
    $params['middlewares'] = $params['middlewares'] ?? [];

    // Variaveis da rota
    $params['variables'] = [];

    // Padrao de validacao das variaveis das rotas
    $patternVariable = '/{(.*?)}/';
    if(preg_match_all($patternVariable, $route, $matches)){
        $route = preg_replace($patternVariable, '(.*?)', $route);
        $params['variables'] = $matches[1];
    }

    // Padrao de validacao da URL
    $patternRoute = '/^'.str_replace('/','\/', $route).'$/';

    // Adicionar a rota dentro da classe
    $this->routes[$patternRoute][$method] = $params;
}

/** Metodo responsavel por definir uma rota no Metodo GET */
public function get($route, $params = [])
{
    return $this->addRoute('GET', $route, $params);
}

/** Metodo responsavel por definir uma rota no Metodo POST */
public function post($route, $params = [])
{
    return $this->addRoute('POST', $route, $params);
}

/** Metodo responsavel por definir uma rota no Metodo PUT */
public function put($route, $params = [])
{
    return $this->addRoute('PUT', $route, $params);
}

/** Metodo responsavel por definir uma rota no Metodo DELETE */
public function delete($route, $params = [])
{
    return $this->addRoute('DELETE', $route, $params);
}

/** Metodo responsavel por retornar o URI desconsiderando o prefixo */
private function getUri()
{
    $uri = $this->request->getUri();
    $uri = parse_url($uri, PHP_URL_PATH);

    // Fatiar a URI com o prefixo
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    // Retornar a URI sem prefixo
    return end($xUri);
}

/** Metodo responsavel por retornar a rota atual */
private function getRoute()
{
    // URI da Request
    $uri = $this->getUri();

    // Metodo HTTP da Request
    $httpMethod = $this->request->getHttpMethod();

    // Validar as rotas
    foreach($this->routes as $patternRoute => $methods){
        // Verificar se a rota bate com o padrao
        if(preg_match($patternRoute, $uri, $matches)){
            // Verificar se o metodo da rota e igual ao metodo da requisicao
            if(!isset($methods[$httpMethod])){
                throw new Exception("Metodo nao permitido", 405);
            }

            // Remover a primeira posicao do array de matches
            unset($matches[0]);

            // Variaveis processadas pela rota
            $keys = $methods[$httpMethod]['variables'];
            $methods[$httpMethod]['variables'] = array_combine($keys, $matches);

            return $methods[$httpMethod];
        }
    }

    throw new Exception("URL nao encontrada", 404);
}

/** Metodo responsavel por executar a rota atual */
public function run()
{
    try{
        // Obter a rota atual
        $route = $this->getRoute();

        // Verificar se a rota e valida
        if(!isset($route['controller'])){
            throw new Exception("A URL nao pode ser processada", 500);
        }

        // Argumentos da rota
        $args = $route['variables'];

        //Reflection da funcao do controller
        $reflection = new ReflectionFunction($route['controller']);
        foreach($reflection->getParameters() as $parameter){
            $name = $parameter->getName();
            $args[$name] = $name == 'request' ? $this->request : ($route['variables'][$name] ?? '');
        }

        // Retornar a execucao da fila de middlewares com a funcao do controller
        return (new Queue($route['middlewares'], $route['controller'], $args))->next($this->request);
    }catch(Exception $e){
        return new Response($e->getCode(), $e->getMessage());
    }
}

/** Metodo responsavel por retornar a URL atual */
public function getCurrentUrl()
{
    return $this->url.$this->getUri();
}

/**Metodo responsavel por retornar a URL */
public function  redirect($route){
//URL
$url = $this->url.$route;
//Executa o redirect
header('location:'.$url);
exit;
}
}
