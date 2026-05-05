<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Models\Entity\Organization;

class Home extends Page {

/***Metodo responsavel por retornar o conteudo (view) da home */
public static function getHome(){
$obOrganization = new Organization;

//Views da home
   $content = View::render('Pages/home',[
    'name'=> $obOrganization->name,
]);

//views da pagina
return parent::getPage('Home - MVC em PHP', $content);
}
}
