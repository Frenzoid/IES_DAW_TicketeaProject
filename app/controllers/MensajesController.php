<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 28/12/17
 * Time: 14:49
 */

namespace liveticket\app\controllers;


use Exception;
use liveticket\app\entities\Mensaje;
use liveticket\core\App;
use liveticket\core\Response;

class MensajesController
{

    public function mostrarChat($id = null){
        try{
            $allUsers = App::get('database')->findAll('usuarios','Usuario');
            $msgUsers = [];

            $mensajes = App::get('database')->findBy(
                'mensajes',
                'Mensaje',
                [
                     'emisorid' => App::get('user')->getId(),
                     'receptorid' =>  App::get('user')->getId()
                ],
                true,
                true
            );

            if($mensajes != null){
                $msgUsersId = [];

                foreach ($mensajes as $mensaje){
                    if($mensaje->getEmisorid() != App::get('user')->getId()){
                        $repeated = false;
                        foreach ($msgUsersId as $msgid){
                            if($msgid == $mensaje->getEmisorid()){
                                $repeated = true;
                            }
                        }

                        if(!$repeated)
                            $msgUsersId[] = $mensaje->getEmisorid();
                    }

                    if($mensaje->getReceptorid() != App::get('user')->getId()){
                        $repeated = false;
                        foreach ($msgUsersId as $msgid){
                            if($msgid == $mensaje->getReceptorid()){
                                $repeated = true;
                            }
                        }

                        if(!$repeated)
                            $msgUsersId[] = $mensaje->getReceptorid();
                    }
                }

                foreach ($msgUsersId as $msgid){
                    $msgUsers[] = App::get('database')->find('usuarios','Usuario',$msgid);
                }
            }

            $mensajesWithUser = null;
            $openchat = null;

            if($id != null){
                $openchat = App::get('database')->find('usuarios','Usuario', $id);
                if($openchat != null){

                    $mensajesRecibidos = App::get('database')->findBy(
                        'mensajes',
                        'Mensaje',
                        [
                            'emisorid' => $id,
                            'receptorid' =>  App::get('user')->getId()
                        ]
                    );

                    $mensajesEnviados = App::get('database')->findBy(
                        'mensajes',
                        'Mensaje',
                        [
                            'emisorid' => App::get('user')->getId(),
                            'receptorid' =>  $id
                        ]
                    );

                    if($mensajesEnviados == null)
                        $mensajesEnviados = [];

                    if($mensajesRecibidos == null)
                        $mensajesRecibidos = [];

                    foreach ($mensajesEnviados as $mensajesEnviado)
                        $mensajesEnviado->mine = true;

                    foreach ($mensajesRecibidos as $mensajesRecibido)
                        $mensajesRecibido->mine = false;


                    $mensajesWithUser = array_merge($mensajesEnviados, $mensajesRecibidos);
                    usort($mensajesWithUser, array($this,  'orderBySentDate'));
                }
            }

            Response::renderView('communications',[
                'allUsers' => $allUsers,
                'openedChats' => $msgUsers,
                'openedChat' => $openchat,
                'messages' => $mensajesWithUser
            ]);

        }catch (Exception $exception) {
            throw $exception;
        }
    }

    public function mandarMensaje($id = null){
        if($id != null && isset($_POST['mensaje']) && !empty($_POST['mensaje'])){
            $mensaje = new Mensaje();
            $mensaje->setEmisorid(App::get('user')->getId());
            $mensaje->setReceptorid($id);
            $mensaje->setMensaje($_POST['mensaje']);
            App::get('database')->insert('mensajes',[
               'emisorid' => $mensaje->getEmisorid(),
               'receptorid' => $mensaje->getReceptorid(),
               'mensaje' => $mensaje->getMensaje()
            ]);

            App::get('router')->redirect('mensajes/' . $id);

        }else
            App::get('router')->redirect('404');
    }

    public function orderBySentDate($a, $b)
    {
        return strtotime($a->getFecha()) - strtotime($b->getFecha());
    }

}