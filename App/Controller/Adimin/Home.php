<?php

namespace App\Controller\Adimin;
use App\Utils\View;


/***metodo responsavel por retornar a renderizacao da pagina de login */
class Home extends Page{
    
/**Metodo responsavel por renderizar a view de home do painel*/
public static function getHome($request){
//Conteudo da Home
$content = View::render('Adimin/modules/home/index',[]);

//Retorna a pagina completa
return parent::getPainel('Home > Painel do Admin',$content,'home');
}
}
