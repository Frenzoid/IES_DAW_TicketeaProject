<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 20/12/17
 * Time: 17:47
 */

namespace liveticket\app\controllers;
use liveticket\app\helpers\GenCaptcha;

class CaptchaController
{
    use GenCaptcha;

    public function showCaptcha()
    {
        $this->genCaptcha();
    }

    public static function checkCaptcha(){
        $code = $_POST['captcha'];
        if($code == $_SESSION['captcha'])
            return true;
        else {
            $_SESSION['error_messages'][] = "Captcha Invalido";
            return false;
        }
    }
}