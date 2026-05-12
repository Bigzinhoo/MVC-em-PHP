<?php

use App\HTTP\Response;
use App\Controller\Adimin;

//ROTA DE LISTAGEM DE DEPOIMENTOS
$obRouter->get('/adimin/testimonials', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\Testimony::getTestimonials($request));
}
]);

//ROTA DE CADASTRO DE NOVO DEPOIMENTOS
$obRouter->get('/adimin/testimonials/new', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\Testimony::getNewTestimony($request));
}
]);

//ROTA DE CADASTRO DE NOVO DEPOIMENTOS(POST)
$obRouter->post('/adimin/testimonials/new', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\Testimony::setNewTestimony($request));
}
]);

//ROTA DE EDICAO DE UM DEPOIMENTOS
$obRouter->get('/adimin/testimonials/{id}/edit', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\Testimony::getEditTestimony($request,$id));
}
]);
//ROTA DE EDICAO DE UM DEPOIMENTOS(POST)
$obRouter->post('/adimin/testimonials/{id}/edit', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\Testimony::setEditTestimony($request,$id));
}
]);

//ROTA DE EDICAO DE UM DEPOIMENTOS
$obRouter->get('/adimin/testimonials/{id}/edit', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\Testimony::getEditTestimony($request,$id));
}
]);
//ROTA DE EXCLUSAO DE UM DEPOIMENTOS
$obRouter->get('/adimin/testimonials/{id}/delete', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\Testimony::getDeleteTestimony($request,$id));
}
]);

//ROTA DE EXCLUSAO DE UM DEPOIMENTOS(POST)
$obRouter->post('/adimin/testimonials/{id}/delete', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\Testimony::setDeleteTestimony($request,$id));
}
]);