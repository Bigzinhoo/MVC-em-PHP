<?php

namespace App\Controller\Pages;


use App\Utils\View;
use App\Models\Entity\Testimonial;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimonials extends Page {

//metodo responsavel por obter a renderização dos itens de depoimentos
private static function getTestimonialsItems($request, &$obPagination){
   //depoimentos
   $itens = '';
   //Quantidade total de registros
   $quantidadeTotal = Testimonial::getTestimonialsCount();
   //pagina atual
   $queryParams = $request->getQueryParams();
   $paginaAtual = $queryParams['page'] ?? 1;

   //instancia de paginacao
   $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 4);

   //obter os depoimentos
   $results = Testimonial::getTestimonials('', 'id DESC', $obPagination->getLimit());

   //renderizar os depoimentos
   foreach ($results as $obTestimonial) {
          $itens .= View::render('Pages/testimonials/item',[
             'nome' => $obTestimonial->nome,
             'mensagem' => $obTestimonial->mensagem,
             'data' => date('d/m/Y H:i:s', strtotime($obTestimonial->data))
            ]);
   }
   //retornar os depoimentos
   return $itens;
}

/***Metodo responsavel por retornar o conteudo (view) dos depoimentos */
public static function getTestimonials($request){
   //conteudo da pagina de depoimentos
   $content = View::render('Pages/testimonials',[
   'itens' => self::getTestimonialsItems($request, $obPagination),
   'pagination' => parent::getPagination($request, $obPagination)
   ]);

//views da pagina
return parent::getPage('DEPOIMENTOS - MVC em PHP', $content);
}

//metodo responsavel por cadastrar um depoimento
public static function insertTestimonial($request){
   $postVars = $request->getPostVars();

   //nova instancia de depoimento
   $obTestimonial = new Testimonial();
   $obTestimonial->nome = $postVars['nome'] ?? '';
   $obTestimonial->mensagem = $postVars['mensagem'] ?? '';
   $obTestimonial->cadastrar();
   return self::getTestimonials($request);
}
}
