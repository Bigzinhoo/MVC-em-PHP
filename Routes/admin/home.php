<?php

use App\HTTP\Response;
use App\Controller\Adimin; 

//ROTA DE ADIMIN
$obRouter->get('/adimin', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\home::getHome($request));
}
]);