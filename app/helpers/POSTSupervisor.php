<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 23/12/17
 * Time: 13:54
 */

namespace liveticket\app\helpers;

trait POSTSupervisor
{
    public static function trim(){
        foreach ($_POST as $clave => $valor){
            $_POST[$clave] = trim($valor);
        }
    }

    public static function detectspecialchars(){
        foreach ($_POST as $clave => $valor) {
            if (preg_match('[\'^£\$%&*()}{#~?><>,|=+¬]', $valor)) {
                $_SESSION['error_messages'][] = 'No se admiten caracteres especiales';
                break;
            }
        }
    }

    public static function removespecialchars() {
        foreach ($_POST as $clave => $valor) {
            preg_replace('/[^A-Za-z0-9\-]/', '', $valor);
        }
    }

    public static function removeTags(){
        foreach ($_POST as $clave => $valor) {
            $_POST[$clave] = strip_tags($valor);
        }
    }
}