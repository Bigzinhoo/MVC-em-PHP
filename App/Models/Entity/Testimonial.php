<?php

namespace App\Models\Entity;
use \WilliamCosta\DatabaseManager\Database;

class Testimonial
{
    //atributos do depoimento
    //id do depoimento
    public $id;

    //nome do usuario
    public $nome;

    //mensagem do depoimento
    public $mensagem;

    //data do depoimento
    public $data;

    //metodo para cadastrar a instancia atual no banco de dados
    public function cadastrar()
    {
    //definir a data
    $this->data = date('Y-m-d H:i:s');

    //inserir o depoimento no banco de dados
    $this->id = (new Database('depoimentos'))->insert([
        'nome' => $this->nome,
        'mensagem' => $this->mensagem,
        'data' => $this->data
    ]);

        //retornar sucesso
        return true;
    }

    //metodo responsavel por obter os depoimentos do banco de dados
    public static function getTestimonials($where = '', $order = '', $limit = '', $fields = '*')
    {
        return (new Database('depoimentos'))->select($where, $order, $limit, $fields)
            ->fetchAll(\PDO::FETCH_CLASS, self::class);
    }

    //metodo responsavel por obter a quantidade total de depoimentos
    public static function getTestimonialsCount($where = '')
    {
        $result = (new Database('depoimentos'))->select($where, '', '', 'COUNT(*) as qtd')
            ->fetchObject();

        return (int) $result->qtd;
    }
}
