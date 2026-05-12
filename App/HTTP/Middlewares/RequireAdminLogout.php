<?php

namespace App\HTTP\Middlewares;
use App\Session\Admin\Login as SessionAdminLogin;


class  RequireAdminLogout{


public function handle($request,$next){
//VERIFICA SE O USUARIO ESTA LOGADO
if(SessionAdminLogin::isLogged()){
    $request->getRouter()->redirect('/adimin');
}
//Continua a execucao
return $next($request);
}

}