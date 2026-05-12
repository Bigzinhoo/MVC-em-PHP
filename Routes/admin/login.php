<?php

namespace Routes\admin;

use App\HTTP\Response;
use App\Controller\Adimin; 

//Classe das rotas de login para uma melhor organizacao das rotas

//ROTA DE LOGIN
$obRouter->get('/adimin/login', [
    'middlewasres'=>[
        'required-admin-logout'
    ],
function ($request){
return new Response(200,Adimin\Login::getLogin($request));
}
]);

//ROTA DE LOGIN POST
$obRouter->post('/adimin/login', [ 
    'middlewares'=>[
        'required-admin-logout'
    ],
function ($request){
return new Response(200,Adimin\Login::setLogin($request));
}
]);

//ROTA DE LOGOUT
$obRouter->get('/adimin/logout',[
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\Login::setLogout($request));
}
]);