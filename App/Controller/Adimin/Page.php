<?php

namespace App\Controller\Adimin;

use App\Utils\View;

class Page{

/***Modulos disponiveis no Painel */
   private static $modules =[
  'home'=>[
    'label'=>'Home',
    'link'=>URL.'/adimin'
  ],
   'testimonials'=>[
    'label'=>'Depoimentos',
    'link'=>URL.'/adimin/testimonials'
  ],
   'Users'=>[
    'label'=>'Usuarios',
    'link'=>URL.'/adimin/users'
  ]
   ];

    /*** Método responsavel por retornar o conteudo da estrutura generica de pagina*/
    public static function getPage($title, $content)
    {
        return View::render('Adimin/page', [
            'title' => $title,
            'content' => $content
        ]);
    }

    /**Metodo responsavel por renderizar a view do menu do painel */
    private static function getMenu($currentModule){
    //Variavel dos links dos modulos
    $links='';
    //ITERA OS MODULOS
    foreach(self::$modules as $hash=>$module){
    $links .= View::render('Adimin/Menu/link',[
    'label'=>$module['label'],
    'link'=>$module['link'],
    'current' => $hash == $currentModule ? 'text-danger' : ''
    ]);
    }

    //RETORNA A RENDERIZACAO DO MENU
    return View::render('Adimin/Menu/box',[
    'links'=>$links
    ]);
    }

    /***Metodo responsavel por renderizar a view do painel com conteudo dinamicos */
    public static function getPainel($title,$content,$currentModule){
    //RENDERIZA A VIEW DO PAINEL
    $contentPainel= View::render('Adimin/Painel',[
   'menu' => self::getMenu($currentModule),
   'content'=>$content
    ]);
    //RETORNA O CONTEUDO E TITULO RENDERIZADO
    return self::getPage($title,$contentPainel);
    }

    /**Metodo responsavel por renderizar o layout de paginacao */
public static function getPagination($request,$obPagination){
    //Paginas
    $pages = $obPagination->getPages();

    //Verifica a quantidade de paginas
    if(count($pages) <= 1) return '';

    //Links
    $links = '';

    //URL atual (sem GETs)
    $url = $request->getRouter()->getCurrentUrl();

    //Gets atuais
    $queryParams = $request->getQueryParams();

    //Renderiza os links
    foreach($pages as $page){
        //Altera a pagina atual
        $queryParams['page'] = $page['page'];

        //Link
        $link = $url.'?'.http_build_query($queryParams);

        //View
        $links .= View::render('Adimin/pagination/link',[
            'page' => $page['page'],
            'link' => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }
    return View::render('Adimin/pagination/box',[                                  
        'links' => $links
    ]);
}

}
