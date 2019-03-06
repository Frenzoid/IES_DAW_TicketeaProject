<?php

namespace liveticket\app\helpers;


trait GenCaptcha{

    private function genCaptcha(){
        // Generacion del string del captcha.
        $string = $this->generateRandomString();
        $_SESSION['captcha'] = $string;

        // Creación y configuración del lienzo
        $alto = 40;
        $ancho = 80;
        $imagen = imagecreatetruecolor($ancho, $alto);
        $rojo = imagecolorallocate($imagen, 255, 0, 0);
        $azul = imagecolorallocate($imagen, 0, 0, 64);


        // Dibujamos la imagen
        imagefill($imagen, 0, 0, $azul);
        imagestring($imagen, 5, 20, 10,$string , $rojo);


        // Dibujamos las lineas
        for ($i = 1; $i <= 6; $i++) {
            $horizontal = rand(20,50);
            imageline($imagen, $horizontal,40,$horizontal,0,$azul);
        }

        // Generación de la imagen.
        header('content-type: image/png');
        imagepng ($imagen);

        // Liberamos la imagen de memoria.
        imagedestroy($imagen);
    }

    private function generateRandomString($length = 5) {
        $randomString = substr(md5(time()),0,$length);
        return $randomString;
    }
}