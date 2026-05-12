<?php

namespace App\Controller\Adimin;
use App\Utils\View;
use \App\Models\Entity\User;
use \App\Session\Admin\Login as SessionAdminLogin;

/***metodo responsavel por retornar a renderizacao da pagina de login */
class Login extends Page{
    public static function getLogin($request ,$errorMessage = null)
    {
        //STATUS
        $status = !is_null($errorMessage) ? Alert::getError($errorMessage) : '';
        //CONTEUDO DA PAGINA DE LOGIN
        $content = View::render('Adimin/login',[
            'status' => $status
        ]);

        /*Retorna a pagina completa*/
        return parent::getPage('Login - Adimin', $content);
    }

    /**metodo responsavel por definir o login do usuario */
    public static function setLogin($request)
    {
     //POST VARS
        $postVars = $request->getPostVars();
        //validacao do login
        $email = $postVars['email'] ?? '';
        $senha = $postVars['senha'] ?? '';

        //busca o usuario por email
        $obUser = \App\Models\Entity\User::getUserByEmail($email);
        //valida o usuario
        if(!$obUser instanceof User ){
        return self::getLogin($request,'Email invalido');
        }
        //valida a senha
        if(!password_verify( $senha ,$obUser->senha)){
        return self::getLogin($request,'Senha invalida');
        }

        //cria a sessão de login(recebendo o objeto do usuario)
        SessionAdminLogin::login($obUser);
        
        //redireciona o usuario para a rota de Admin
        $request->getRouter()->redirect('/adimin');
    }

    public static function setLogout($request){
    //DESTROI A SESSAO DE LOGIN
    SessionAdminLogin::logout();

    //REDIRECIONA O USUARIO PARA A TELA DE LOGIN
    $request->getRouter()->redirect('/adimin/login');
    }
}
