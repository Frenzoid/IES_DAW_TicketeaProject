<?php
/**
 * Created by PhpStorm.
 * User: dwes
 * Date: 24/11/17
 * Time: 17:43
 */

namespace liveticket\core;

class Security
{
    static public function isUserGranted($role)
    {
        if ($role === 'ROLE_ANONIMO')
            return true;

        $usuario = App::get('user');
        if (is_null($usuario))
            return false;

        $valor_role = App::get('config')['security']['roles'][$role];
        $valor_role_usuario = App::get('config')['security']['roles'][$usuario->getRole()];

        return $valor_role_usuario >= $valor_role;
    }

    public static function getSalt()
    {
        return substr (
            strtr (
                base64_encode (
                    openssl_random_pseudo_bytes (22)
                ),
                '+', '.'),
        0, 22
        );
    }

    public static function encrypt($password, $salt)
    {
        /* 2y es el selector de algoritmo bcrypt, ver http://php.net/crypt
        05 el algoritmo se ejecuta 5 veces, ver http://php.net/crypt */
        return crypt ($password, '$2y$05$' . $salt);
    }

    public static function checkPassword(
        $password, $bdSalt, $bdPassword)
    {
        /* 2y es el selector de algoritmo bcrypt, ver http://php.net/crypt
        05 el algoritmo se ejecuta 5 veces, ver http://php.net/crypt */
        $hashed_pass = self::encrypt($password, $bdSalt);
        if ($hashed_pass == $bdPassword)
            return true;
        else
            return false;
    }
}