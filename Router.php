<?php

namespace MVC;

class Router
{
    public $getRoutes = [];
    public $postRoutes = [];
    protected $base = '';

    public function get($url, $fn)
    {
        $this->getRoutes[$this->base . $url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$this->base .$url] = $fn;
    }


    public function setBaseURL($base){
        $base = trim($base);
        $this->base = rtrim($base, '/');
    }

    public function comprobarRutas()
    {


        $currentUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $method = $_SERVER['REQUEST_METHOD'];
        // debuguear($currentUrl);
        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }
        

        if ( $fn ) {
            // Call user fn va a llamar una función cuando no sabemos cual sera
            call_user_func($fn, $this); // This es para pasar argumentos
        } else {
            // debuguear($_SERVER);
            if( empty($_SERVER['HTTP_X_REQUESTED_WITH'])){
                $this->render('pages/notfound');
            
            }else{
                getHeadersApi();
                echo json_encode(["ERROR" => "PÁGINA NO ENCONTRADA"]);
            }
        }
    }

    public function render($view, $datos = [], $layout = 'layout') {
        // Leer lo que le pasamos a la vista
        foreach ($datos as $key => $value) {
            $$key = $value;  // Variable dinámica
        }
    
        ob_start(); // Almacenamiento en memoria temporal...
    
        // Incluir la vista solicitada
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Captura el contenido de la vista y limpia el buffer
    
        // Incluir el layout especificado (por defecto, 'layout.php')
        include_once __DIR__ . "/views/{$layout}.php";
    }
    public function load($view, $datos = []){
        foreach ($datos as $key => $value) {
            $$key = $value;  // Doble signo de dolar significa: variable variable, básicamente nuestra variable sigue siendo la original, pero al asignarla a otra no la reescribe, mantiene su valor, de esta forma el nombre de la variable se asigna dinamicamente
        }

        ob_start(); // Almacenamiento en memoria durante un momento...

        // entonces incluimos la vista en el layout
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); // Limpia el Buffer
        return $contenido;
    }

    public function printPDF($ruta){

        header("Content-type: application/pdf");
        header("Content-Disposition: inline; filename=filename.pdf");
        @readfile(__DIR__ . '/storage/' . $ruta );
    
    }
}
