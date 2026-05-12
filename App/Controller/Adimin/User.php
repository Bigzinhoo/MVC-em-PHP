<?php

namespace App\Controller\Adimin;

use App\Utils\View;
use App\Models\Entity\User as EntityUser;
use \WilliamCosta\DatabaseManager\Pagination;

class User extends Page{

    /** Metodo responsavel por obter a renderizacao dos itens de Usuario */
    private static function getUserItems($request, &$obPagination){
        //Usuarios
        $itens = '';

        //Quantidade total de registros
        $quantidadeTotal = EntityUser::getUserCount();

        //pagina atual
        $queryParams = $request->getQueryParams();
        $paginaAtual = $queryParams['page'] ?? 1;

        //instancia de paginacao
        $obPagination = new Pagination($quantidadeTotal, $paginaAtual, 4);

        //obter os usuarios
        $results = EntityUser::getUsers('', 'id DESC', $obPagination->getLimit());

        //renderizar os usuarios
        foreach ($results as $obUser) {
            $itens .= View::render('Adimin/modules/users/itens', [
                'id' => $obUser->id,
                'nome' => $obUser->nome,
                'email' => $obUser->email
            ]);
        }

        //retornar os usuarios
        return $itens;
    }

    /** Metodo responsavel por renderizar a view de Usuarios */
    public static function getUsers($request){
        //Conteudo dos Depoimentos
        $content = View::render('Adimin/modules/users/index', [
            'itens' => self::getUserItems($request, $obPagination),
            'pagination'=>parent::getPagination($request,$obPagination),
            'status'=> self::getStatus($request)
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Usuarios > MVC', $content, 'users');
    }

    /**Metodo responsavel por cadastrar um novo  usuario */
    public static function getNewUser($request){
//Conteudo do formulario de usuario
        $content = View::render('Adimin/modules/users/form', [
         'title'=>'Cadastrar Usuario',
         'nome'=>'',
         'email'=>'',
         'senha_required'=>'required',
         'status'=>self::getStatus($request)
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Cadastrar Usuario > MVC', $content, 'users');
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
    return Alert::getSuccess('Usuario cadastrado com sucesso.');
    break;
    case 'updated':
    return Alert::getSuccess('Usuario atualizado com sucesso.');
    break;
    case 'deleted':
    return Alert::getSuccess('Usuario excluido com sucesso.');
    break;
    case 'duplicated':
    return Alert::getError('E-mail ja esta sendo utilizado por outro usuario.');
    break;
    }
    }

    /**Metodo responsavel por gravar a atualizacao de um  usuario */
   public static function setEditUser($request,$id){
      //Obtem o usuario do Banco de Dados
      $obUser=EntityUser::getUserById($id);
      
      //Valida a instancia
      if(!$obUser instanceof EntityUser){
      $request ->getRouter()->redirect('/adimin/users');
      }
      //PostVars
      $postVars= $request -> getPostVars();

      //Atualiza a instancia
      $email = $postVars['email'] ?? '';
      $senha = $postVars['senha'] ?? '';

      $obUserDuplicated = EntityUser::getUserByEmail($email);
      if($obUserDuplicated instanceof EntityUser && $obUserDuplicated->id != $obUser->id){
      $request -> getRouter()->redirect('/adimin/users/'.$obUser->id.'/edit?status=duplicated');
      }

      $obUser->nome = $postVars['nome'] ?? $obUser->nome;
      $obUser->email = $email ?: $obUser->email;
      $obUser->senha = strlen($senha) ? password_hash($senha, PASSWORD_DEFAULT) : $obUser->senha;
      $obUser->atualizar();
      //Redireciona o usuario
   $request->getRouter()->redirect('/adimin/users/'.$obUser->id.'/edit?status=updated');
    }

    /**Metodo responsavel por cadastrar de um novo usuario */
    public static function setNewUser($request){
   //PostVars
   $postVars= $request -> getPostVars();
   $email= $postVars['email'] ?? '';
   $nome= $postVars['nome'] ?? '';
   $senha= $postVars['senha'] ?? '';

   //Valida o email do usuario
   $obUser = EntityUser::getUserByEmail($email);
   if($obUser instanceof EntityUser){
    //Redireciona o usuario
    $request -> getRouter()->redirect('/adimin/users/new?status=duplicated');
   }

   //Nova instancia de usuario
   $obUser= new EntityUser;
   $obUser->nome = $postVars['nome'] ?? '';
   $obUser->email = $postVars['email'] ?? '';
   $obUser->senha = password_hash($senha, PASSWORD_DEFAULT);
   $obUser->cadastrar();

   //Redireciona o usuario
   $request->getRouter()->redirect('/adimin/users/'.$obUser->id.'/edit?status=created');
    }
    /**Metodo responsavel por editar um usuario */
    public static function getEditUser($request,$id){
      //Obtem o usuario do Banco de Dados
      $obUser=EntityUser::getUserById($id);
      
      //Valida a instancia
      if(!$obUser instanceof EntityUser){
      $request ->getRouter()->redirect('/adimin/users');
      }

      //Conteudo do formulario de usuarios
        $content = View::render('Adimin/modules/users/form', [
         'title'=>'Editar Usuario',
         'nome'=>$obUser->nome,
         'email'=>$obUser->email,
         'senha_required'=>'',
         'status' => self::getStatus($request)
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Editar Usuario > MVC', $content, 'users');
    }

        /**Metodo responsavel por excluir um usuario */
    public static function getDeleteUser($request,$id){
      //Obtem o usuario do Banco de Dados
      $obUser=EntityUser::getUserById($id);
      
      //Valida a instancia
      if(!$obUser instanceof EntityUser){
      $request ->getRouter()->redirect('/adimin/users');
      }

      //Conteudo do formulario de usuarios
        $content = View::render('Adimin/modules/users/delete', [
         'nome'=>$obUser->nome,
         'email'=>$obUser->email
        ]);

        //Retorna a pagina completa
        return parent::getPainel('Excluir Usuario > MVC', $content, 'users');
    }
     
    /**Metodo responsavel por excluir um usuario */
     public static function setDeleteUser($request,$id){
      //Obtem o usuario do Banco de Dados
      $obUser=EntityUser::getUserById($id);
      
      //Valida a instancia
      if(!$obUser instanceof EntityUser){
      $request ->getRouter()->redirect('/adimin/users');
      }

      //Exclui o usuario
      $obUser->excluir();
      //Redireciona o usuario
   $request->getRouter()->redirect('/adimin/users?status=deleted');
    }

}
