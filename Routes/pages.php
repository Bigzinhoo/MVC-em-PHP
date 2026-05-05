<?php

namespace Routes\pages;

use App\HTTP\Response;
use App\Controller\Pages; 

//ROTA DE PAGINA HOME
$obRouter->get('/', [
function (){
return new Response(200, Pages\Home::getHome());
}
]);

//ROTA DE PAGINA SOBRE
$obRouter->get('/sobre', [
function (){ 
return new Response(200, Pages\About::getAbout());  
}
]);

//ROTA DE PAGINA DEPOIMENTOS
$obRouter->get('/depoimentos', [
function ($request){ 
return new Response(200, Pages\Testimonials::getTestimonials($request));  
}
]);

//ROTA DE PAGINA DEPOIMENTOS (insert)
$obRouter->post('/depoimentos', [
function ($request){ 
return new Response(200, Pages\Testimonials::insertTestimonial($request));  
}
]);
