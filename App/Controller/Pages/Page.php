<?php

namespace App\Controller\Pages;

use App\Utils\View;

class Page {

/***Metodo responsavel por retornar o topo da pagina */
private static function getHeader(){
    return View::render('Pages/header');
}

/***Metodo responsavel por retornar o rodape da pagina */
private static function getFooter(){
    return View::render('Pages/footer');
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
        $links .= View::render('Pages/Pagination/link',[
            'page' => $page['page'],
            'link' => $link,
            'active' => $page['current'] ? 'active' : ''
        ]);
    }
    return View::render('Pages/Pagination/box',[                                  
        'links' => $links
    ]);
}

/***Metodo responsavel por retornar o conteudo (view) daa nossa pagina generica */
public static function getPage($title, $content){
    return View::render('Pages/page',[
    'title' => $title,
    'header' => self::getHeader(),
    'content' => $content,
    'footer' => self::getFooter()
]);
}

}
