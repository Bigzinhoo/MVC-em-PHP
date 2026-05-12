<?php

namespace App\Session\Admin;

class Login{

/**Metodo responsavel por iniciar a sessão */
private static function init(){
  //verifica se a sessão não esta ativa
  if(session_status() != PHP_SESSION_ACTIVE){
    //inicia a sessão
    session_start();
  }
}

/**Metodo responsavel por criar o login do usuario  */
public static function login($obUser){
//inicia a sessão
self::init();

//Sessao sempre com Array para evitar erros de indice
//Define a sessao do usuario logado
$_SESSION['admin']['usuario'] = [
  'id' => $obUser->id,
  'nome' => $obUser->nome,
  'email' => $obUser->email
];
return true;
}

/***Metodo responsavel por veriicar se o usuario esta logado */
public static function isLogged(){

//INICIA A SESSAO
self::init();

//RETORNA A VERIFICACAO
return isset($_SESSION['admin']['usuario']['id']);
}

/***metodo responsavel por executar o logout do usuario*/
public static function logout(){
  //INICIA A SESSAO
  self::init();

  //DESLOGA O USUARIO
  unset($_SESSION['admin']['usuario']);

  return true;
}

}