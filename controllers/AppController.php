<?php

namespace Controllers;

use MVC\Router;

class AppController {
    public static function index(Router $router){
        isAuth();
        // hasPermission([
        //     'ADMIN',
        // ]);
        $router->render('pages/index', []);
    }

}