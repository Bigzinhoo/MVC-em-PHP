<?php

namespace App\Models\Entity;

use \WilliamCosta\DatabaseManager\Database;

class User{
    /***id do usuário */
    public $id;

    /***nome do usuário */
    public $nome;

    /***email do usuário */
    public $email;

    /***senha do usuário */
    public $senha;

     /***metodo responsavel por cadastrar um novo usuario no banco */
    public function cadastrar()
    {
        $this->id = (new Database('usuarios'))->insert([
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);

        return true;
    }

    /**Metodo responsavel por atualizar os dados do usuario no banco */
    public function atualizar()
    {
        return (new Database('usuarios'))->update('id = '.$this->id, [
            'nome' => $this->nome,
            'email' => $this->email,
            'senha' => $this->senha
        ]);
    }

    /**Metodo responsavel por excluir o usuario do banco */
    public function excluir()
    {
        return (new Database('usuarios'))->delete('id = '.$this->id);
    }

    /**metodo responsavel por retornar um usuario com base no email para previnir SQL Injection */
    public static function getUserByEmail($email)
    {
        return (new Database('usuarios'))->select('email = "'.addslashes($email).'"')->fetchObject(self::class);
    }

    /**Metodo responsavel por retornar um usuario com base no seu Id */
    public static function getUserById($id){
    $results = self::getUsers('id = '.(int)$id);

    return $results[0] ?? null;
    }

    //metodo responsavel por obter os usuarios do banco de dados
    public static function getUsers($where = '', $order = '', $limit = '', $fields = '*')
    {
        return (new Database('usuarios'))->select($where, $order, $limit, $fields)
            ->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    //metodo responsavel por obter a quantidade total de usuarios
    public static function getUserCount($where = '')
    {
        $result = (new Database('usuarios'))->select($where, '', '', 'COUNT(*) as qtd')
            ->fetchObject();

        return (int) $result->qtd;
    }
}
