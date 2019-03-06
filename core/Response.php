<?php

namespace liveticket\core;

use Exception;

class Response
{
    public static function renderView($name, $data = array())
    {
        extract ($data);

        if (isset($_SESSION['error_messages']))
        {
            $error_messages = $_SESSION['error_messages'];
            $_SESSION['error_messages'] = [];
        }
        else
            $error_messages = [];


        try{

            $provincias = App::get('database')->findAll(
                'provincias',
                'Provincia'
            );

            $categorias = App::get('database')->findAll(
                'categorias',
                'Categoria'
            );

        }catch(Exception $pdoException){
            throw new Exception('No se ha podido ejecutar la query del header: ' . $pdoException->getMesage());
        }

        $usuario = App::get('user');

        ob_start ();

        require __DIR__ . "/../app/views/$name.view.php";

        $mainContent = ob_get_clean ();

        require __DIR__ . '/../app/views/layout.view.php';
    }
}