<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/12/17
 * Time: 17:36
 */

namespace liveticket\app\controllers;
use liveticket\core\App;
use liveticket\core\Security;
use Exception;

class IdiomaController
{

    private function userDBlangUpdate(){
        try{

            App::get('database')->update(
                'usuarios',
                ['lang' => $_SESSION['language']],
                ['id' => App::get('user')->getId()]
            );

        }catch (Exception $exception){
            throw $exception;
        }
    }

    public function idiomaEspanol(){

        $_SESSION['language'] = "es_ES.utf8";

        if(Security::isUserGranted("ROLE_COMPRADOR")){
            $this->userDBlangUpdate();
        }

        App::get('router')->redirect($_SESSION['uri']);
    }

    public function idiomaIngles(){

        $_SESSION['language'] = "en_GB.utf8";

        if(Security::isUserGranted("ROLE_COMPRADOR")){
            $this->userDBlangUpdate();
        }

        App::get('router')->redirect($_SESSION['uri']);
    }

}