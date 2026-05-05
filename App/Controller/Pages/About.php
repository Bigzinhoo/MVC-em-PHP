<?php

namespace App\Controller\Pages;

use App\Utils\View;
use App\Models\Entity\Organization;

class About extends Page {

/***Metodo responsavel por retornar o conteudo (view) da pagina Sobre */
public static function getAbout(){

$obOrganization = new Organization;

//Views da home
   $content = View::render('Pages/about',[
    'name'=> $obOrganization->name,
    'site' => $obOrganization->site,
    'description' => $obOrganization->description
]);

//views da pagina
return parent::getPage('Sobre - MVC em PHP', $content);
}
}