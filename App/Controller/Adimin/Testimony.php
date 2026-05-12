<?php

namespace App\Controller\Adimin;

use App\Utils\View;
use App\Models\Entity\Testimonial as EntityTestimony;
use \WilliamCosta\DatabaseManager\Pagination;

class Testimony extends Page{

    /** Metodo responsavel por obter a renderizacao dos itens de depoimentos */
    private static function getTestimonialsItems($request, &$obPagination){
        //depoimentos
        $itens = '';

        //Quantidade total de registros
        $quantidadeTotal = EntityTestimony::getTestimonialsCount();

        //pagina atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //instancia de paginacao
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 4);

        //obter os depoimentos
        $results = EntityTestimony::getTestimonials('', 'id DESC', $obPagination->getLimit());

        //renderizar os depoimentos
        foreach ($results as $obTestimony) {
            $itens .= View::render('Adimin/modules/testimonials/itens', [
                'id' => $obTestimony->id,
                'nome' => $obTestimony->nome,
                'mensagem' => $obTestimony->mensagem,
                'data' => date('d/m/Y H:i:s', strtotime($obTestimony->data))
            ]);
        }

        //retornar os depoimentos
        return $itens;
    }

    /** Metodo responsavel por renderizar a view de depoimentos */
    public static function getTestimonials($request){
        //Conteudo dos Depoimentos
        $content = View::render('Adimin/modules/testimonials/index', [
            'itens' => self::getTestimonialsItems($request, $obPagination),
            'pagination'=>parent::getPagination($request,$obPagination),
            'status'=> self::getStatus($request)
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Depoimentos > MVC', $content, 'testimonials');
    }

    /**Metodo responsavel por cadastrar um novo depoimento de usuario */
    public static function getNewTestimony($request){
//Conteudo do fformulario de depoimentos
        $content = View::render('Adimin/modules/testimonials/form', [
         'title'=>'Cadastrar Depoimento',
         'nome'=>'',
         'mensagem'=>'',
         'status'=>''
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Cadastrar Depoimento > MVC', $content, 'testimonials');
    }

    /**Metodo responsavel por retornar a mensagem de status */
    private static function getStatus($request){
    //QueryParams
    $queryParams = $request ->getQueryParams();

    //Status
    if(!isset($queryParams['status'])) return '';

    //Mensagens de Status
    switch($queryParams['status']){
    case 'created':
    return Alert::getSuccess('Depoimento criado com sucesso.');
    break;
    case 'updated':
    return Alert::getSuccess('Depoimento atualizado com sucesso.');
    break;
    case 'deleted':
    return Alert::getSuccess('Depoimento excluido com sucesso.');
    break;
    
    }
    }

    /**Metodo responsavel por gravar a atualizacao de um depoimento depoimento de usuario */
   public static function setEditTestimony($request,$id){
      //Obtem o depoimento do Banco de Dados
      $obTestimony=EntityTestimony::getTestimonialsById($id);
      
      //Valida a instancia
      if(!$obTestimony instanceof EntityTestimony){
      $request ->getRouter()->redirect('/adimin/testimonials');
      }
      //PostVars
      $postVars= $request -> getPostVars();

      //Atualiza a instancia
      $obTestimony->nome = $postVars['nome'] ?? $obTestimony->nome;
      $obTestimony->mensagem = $postVars['mensagem'] ?? $obTestimony->mensagem;
      $obTestimony->atualizar();
      //Redireciona o usuario
   $request->getRouter()->redirect('/adimin/testimonials/'.$obTestimony->id.'/edit?status=updated');
    }

    /**Metodo responsavel por cadastrar de um novo depoimento */
    public static function setNewTestimony($request){
   //PostVars
   $postVars= $request -> getPostVars();

   //Nova instancia de depoimento
   $obTestimony= new EntityTestimony;
   $obTestimony->nome = $postVars['nome'] ?? '';
   $obTestimony->mensagem = $postVars['mensagem'] ?? '';
   $obTestimony->cadastrar();

   //Redireciona o usuario
   $request->getRouter()->redirect('/adimin/testimonials/'.$obTestimony->id.'/edit?status=created');
    }
    /**Metodo responsavel por editar um depoimento de usuario */
    public static function getEditTestimony($request,$id){
      //Obtem o depoimento do Banco de Dados
      $obTestimony=EntityTestimony::getTestimonialsById($id);
      
      //Valida a instancia
      if(!$obTestimony instanceof EntityTestimony){
      $request ->getRouter()->redirect('/adimin/testimonials');
      }

      //Conteudo do formulario de depoimentos
        $content = View::render('Adimin/modules/testimonials/form', [
         'title'=>'Editar Depoimento',
         'nome'=>$obTestimony->nome,
         'mensagem'=>$obTestimony->mensagem,
         'status' => self::getStatus($request)
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Editar Depoimento > MVC', $content, 'testimonials');
    }

        /**Metodo responsavel por excluir um depoimento de usuario */
    public static function getDeleteTestimony($request,$id){
      //Obtem o depoimento do Banco de Dados
      $obTestimony=EntityTestimony::getTestimonialsById($id);
      
      //Valida a instancia
      if(!$obTestimony instanceof EntityTestimony){
      $request ->getRouter()->redirect('/adimin/testimonials');
      }

      //Conteudo do formulario de depoimentos
        $content = View::render('Adimin/modules/testimonials/delete', [
         'nome'=>$obTestimony->nome,
         'mensagem'=>$obTestimony->mensagem
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Excluir Depoimento > MVC', $content, 'testimonials');
    }
     
    /**Metodo responsavel por excluir um depoimento */
     public static function setDeleteTestimony($request,$id){
      //Obtem o depoimento do Banco de Dados
      $obTestimony=EntityTestimony::getTestimonialsById($id);
      
      //Valida a instancia
      if(!$obTestimony instanceof EntityTestimony){
      $request ->getRouter()->redirect('/adimin/testimonials');
      }

      //Exclui o depoimento
      $obTestimony->excluir();
      //Redireciona o usuario
   $request->getRouter()->redirect('/adimin/testimonials?status=deleted');
    }

}