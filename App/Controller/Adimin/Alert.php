<?php

namespace App\Controller\Adimin;
use \App\Utils\View;

class Alert{

/***Metodo responsavel por retornar uma mensagem de sucesso */
public static function getSuccess($message){
 return View::render('Adimin/Alert/status',[
    'tipo' => 'success',
    'mensagem' => $message
 ]);
}

/***Metodo responsavel por retornar uma mensagem de error */
public static function getError($message){
 return View::render('Adimin/Alert/status',[
    'tipo' => 'danger',
    'mensagem' => $message
 ]);
}
}
