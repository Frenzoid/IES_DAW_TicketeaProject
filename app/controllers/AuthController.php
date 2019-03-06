<?php
/**
 * Created by PhpStorm.
 * User: dwes
 * Date: 17/11/17
 * Time: 18:32
 */

namespace liveticket\app\controllers;


use liveticket\app\entities\Usuario;
use liveticket\core\App;
use liveticket\core\Response;
use liveticket\core\Security;
use liveticket\app\controllers;

class AuthController
{
    public function login()
    {
        if(App::get ('user') === null)
            Response::renderView('login');
        else
            App::get('router')->redirect('');
    }

    public function checkLogin()
    {
        if (isset($_POST['nombre']) && !empty($_POST['nombre']) &&
            isset($_POST['contraseña']) && !empty($_POST['contraseña'])) {
            /**
             * @var Usuario $usuario
             */

            $usuario = App::get('database')->findOneBy(
                'usuarios', 'Usuario',
                [
                    'nombre' => $_POST['nombre'],
                    'email' => $_POST['nombre']
                ],
                false,
                true
            );

            if(count($_SESSION['error_messages']) != 0)
                App::get('router')->redirect('login');

            if (!is_null($usuario) && Security::checkPassword(
                    $_POST['contraseña'],
                    $usuario->getSalt(),
                    $usuario->getPasswd()) === true)
            {
                $_SESSION['usuario'] = $usuario->getId();

                if($usuario->getLang() != null)
                    $_SESSION['language'] = $usuario->getLang();

                App::get('router')->redirect('');

            } else {
                $_SESSION['error_messages'][] = 'Credenciales Incorrectas';

                App::get('router')->redirect('login');
            }
        }
        else
        {
            if(!isset($_POST['nombre']) || empty($_POST['nombre']))
                $_SESSION['error_messages'][] = 'No puedes dejar el nombre vacio';

            if(!isset($_POST['contraseña']) || empty($_POST['contraseña']))
                $_SESSION['error_messages'][] = 'No puedes dejar la contraseña vacia';

            App::get('router')->redirect('login');
        }
    }

    public function logout()
    {
        if (isset ($_SESSION['usuario']))
        {
            $_SESSION['usuario'] = null;
            unset ($_SESSION['usuario']);
        }
        App:: get ('router')->redirect('');
    }

    public function unauthorized()
    {
        header ('HTTP/1.1 403 Forbidden', true, 403);
        Response:: renderView ('403');
    }
}