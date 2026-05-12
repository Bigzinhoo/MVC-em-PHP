<?php

namespace App\HTTP\Middlewares;


class Maintenance{

/***Metodo responsavel por executar o middleware /verificar se o sistema esta em manutencao  */
public function handle($request, $next){
    // Verificar se o sistema esta em manutencao
if(getenv('MAINTENANCE') == 'true'){
    throw new \Exception("O sistema está em manutenção.", 200);
}
//Executa o proximo nivel do middleware
return $next($request);
}
}
