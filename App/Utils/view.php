<?php 

namespace App\Utils;

class View {

/**variavel responsavel por armazenar os dados da classe */
private static $vars = [];

/***Metodo responsavel por definir os dados inicias da classe */
public static function init($vars = []){
self::$vars=$vars;
}

/*** metodo responsavel por retornar conteudo de uma view */
private static function getContentView($view){
$file = __DIR__. '/../../resources/Views/'.$view.'.html';

return file_exists($file) ? file_get_contents($file) : '';
}

/***metodo responsavel por retornar conteudo renderizado de uma view */
public static function render($view, $vars = []){
//conteudo da view
$contentView = self::getContentView($view);

//Merge/unir os dados da classe com os dados passados por parametro
$vars = array_merge(self::$vars, $vars);

$keys = array_keys($vars);
$keys = array_map(function($item){
    return '{{'.$item.'}}';
}, $keys);

//retorna o conteudo renderizado da view
return str_replace($keys, array_values($vars), $contentView);
}
}
