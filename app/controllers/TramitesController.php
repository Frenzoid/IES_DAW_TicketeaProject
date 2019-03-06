<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 25/12/17
 * Time: 21:57
 */

namespace liveticket\app\controllers;

use Exception;
use liveticket\app\entities\Factura;
use liveticket\core\App;
use liveticket\core\Response;
use liveticket\app\helpers\GetRemoteAddr;

class TramitesController
{
    use GetRemoteAddr;

    public function tramitar($id = null){

        try{

            if($id != null)
                $evento = App::get('database')->find('eventos', 'Evento', $id);

            if(!isset($evento) || $evento == null){
                App::get('router')->redirect('404');
                return false;
            }


            $tramite = new Factura();

            if($this->validate($evento)){

                $evento->setNER(strval(intval($evento->getNER() - intval($_POST['numentradas']))));

                $tramite->setCantidad($_POST['numentradas']);
                $tramite->setEventoid($evento->getId());
                $tramite->setUsuarioid(App::get('user')->getId());
                $tramite->setBarcode($tramite->getUsuarioid() . $tramite->getEventoid() . $tramite->getCantidad() . "-" . time() . "-" . rand(0,100));

                try{
                    $tramite->setIP($this->returnRemoteIp());
                }
                catch (Exception $exception){
                    throw $exception;
                }

                App::get('database')->update('eventos',
                [
                    'NER' => $evento->getNER()
                ],
                [
                    'id' => $evento->getId()
                ]);

                App::get('database')->insert('facturas',[
                    'usuarioid' => $tramite->getUsuarioid(),
                    'eventoid' => $tramite->getEventoid(),
                    'cantidad' => $tramite->getCantidad(),
                    'barcode' => $tramite->getBarcode(),
                    'IP' => $tramite->getIP()
                ]);

                App::get('router')->redirect('tramites');
            }
        }
        catch(Exception $exception)
        {
            throw $exception;
        }

    }

    public function mostrarTickets($id = null){

        try{

            if($id == null){
                $id = App::get('user')->getId();
            }

            $tickets = App::get('database')->findBy('facturas','Factura',[ 'usuarioid'  => $id]);

            foreach ($tickets as $ticket){
                $evento = App::get('database')->find('eventos','Evento',$ticket->getEventoid());
                $ticket->EPoster = $evento->getEPoster();
                $ticket->ENombre = $evento->getENombre();
                $ticket->EFecha = $evento->getFEvento();
                $ticket->EPrecio = $evento->getPEntrada();
            }

            Response::renderView(
            'ticketslisted',
            [
                'facturas' => $tickets
            ]);

        }catch(Exception $exception){
            throw $exception;
        }
    }

    private function validate($evento){
        if(!isset($_POST['numentradas']) || $_POST['numentradas'] == "")
            $_SESSION['error_messages'][] = "No puedes dejar las entradas vacias";

        if(isset($_POST['numentradas']) && $_POST['numentradas'] == "0")
            $_SESSION['error_messages'][] = "La cantidad de entradas no puede ser 0";

        if(intval($evento->getNER()) < intval($_POST['numentradas']))
            $_SESSION['error_messages'][] = "No hay suficientes entradas. Entradas disponibles: " . $evento->getNER();

        if((strtotime(time()) < strtotime($evento->getFVInicio()) || strtotime(time()) > strtotime($evento->getFVFinal())))
            $_SESSION['error_messages'][] = "No estas en el plazo de comprar entradas";


        if(count($_SESSION['error_messages']) == 0)
            return true;
        else
        {
            try{
                $eventcreator = App::get('database')->find('usuarios', 'Usuario', $evento->getIdCreador());
                Response:: renderView(
                    'eventdetails',
                    [ 'event' => $evento,
                    'eventcreator' => $eventcreator]
                );
            }
            catch (Exception $exception){
                throw $exception;
            }
        }

        return false;
    }
}