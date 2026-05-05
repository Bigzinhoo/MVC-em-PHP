<?php

require_once __DIR__ . '/Includes/app.php';
use App\HTTP\Router;   
//inicia o rotiador
$obRouter = new Router(URL);

//inclui as rotas de paginas
include __DIR__ . '/Routes/pages.php';


//imprime a response da rota
$obRouter->run()->sendResponse();
