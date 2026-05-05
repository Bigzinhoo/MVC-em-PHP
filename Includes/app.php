<?php

// Classe de inicialização do projeto

require_once __DIR__ . '/../vendor/autoload.php';

use App\Utils\View; 
use \WilliamCosta\DotEnv\Environment;
use \WilliamCosta\DatabaseManager\Database;
use App\HTTP\Middlewares\Queue;

//carrega as variaveis de ambiente
Environment::load(__DIR__ . '/..');


//define as configurações do banco de dados
Database::config(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS'),
    getenv('DB_PORT')
);

//define o mapeamento de middlewares
Queue::setMap([
    'maintenance' => App\Http\Middlewares\Maintenance::class
]);

//define a fila de middlewares padrao
Queue::setDefault([
    'maintenance' 
]);

//define o valor de URL para a constante URL
define('URL', getenv('URL'));

//define o valor de URL para a classe View
View::init([
    'URL' => URL
]);
