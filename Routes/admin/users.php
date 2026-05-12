<?php

use App\HTTP\Response;
use App\Controller\Adimin;

//ROTA DE LISTAGEM DE USUARIO
$obRouter->get('/adimin/users', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\User::getUsers($request));
}
]);

//ROTA DE CADASTRO DE NOVO USUARIO
$obRouter->get('/adimin/users/new', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\User::getNewUser($request));
}
]);

//ROTA DE CADASTRO DE NOVO USUARIO(POST)
$obRouter->post('/adimin/users/new', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request){
return new Response(200,Adimin\User::setNewUser($request));
}
]);

//ROTA DE EDICAO DE UM USUARIO
$obRouter->get('/adimin/users/{id}/edit', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\User::getEditUser($request,$id));
}
]);
//ROTA DE EDICAO DE UM USUARIO(POST)
$obRouter->post('/adimin/users/{id}/edit', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\User::setEditUser($request,$id));
}
]);

//ROTA DE EXCLUSAO DE UM USUARIO
$obRouter->get('/adimin/users/{id}/delete', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\User::getDeleteUser($request,$id));
}
]);

//ROTA DE EXCLUSAO DE UM USUARIO(POST)
$obRouter->post('/adimin/users/{id}/delete', [
    'middlewares'=>[
        'required-admin-login'
    ],
function ($request,$id){
return new Response(200,Adimin\User::setDeleteUser($request,$id));
}
]);
