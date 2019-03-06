<?php
/**
 * Created by PhpStorm.
 * User: dwes
 * Date: 8/12/17
 * Time: 19:17
 */

namespace liveticket\app\controllers;

use liveticket\core\{
    App, Response, Security
};

use liveticket\app\entities\Evento;
use liveticket\app\exceptions\UploadException;
use Exception;

class EventosController
{

    public function eventosPortada(){
        try{

            $vars = [];

            $eventosDisponibles = App::get('database')->compareDates(
                'eventos',
                'Evento',
                [
                    'FVInicio' => '<=',
                    'FVFinal' => '>'
                ]
            );

            if(count($eventosDisponibles) > 8)
                $eventosDisponibles = array_slice ( $eventosDisponibles ,0 , 8);



            $eventosFuturos = App::get('database')->compareDates(
                'eventos',
                'Evento',
                [
                    'FVInicio' => '>'
                ]
            );

                if(count($eventosFuturos) > 8)
                    $eventosFuturos = array_slice ( $eventosFuturos ,0 , 8);


            if(App::get('user') != null && App::get('user')->getProvincia() != null) {
                $eventosProvinciales = [];

                foreach ($eventosDisponibles as $event){
                    if($event->getEProvincia() == App::get('user')->getProvincia())
                        array_push($eventosProvinciales, $event);
                }

                if (count($eventosProvinciales) > 8)
                    $eventosProvinciales = array_slice($eventosProvinciales, 0, 8);


                $vars = array_merge(['eventosProvinciales' => $eventosProvinciales], $vars);
            }

            $vars = array_merge([
                'eventosDisponibles'=>$eventosDisponibles,
                'eventosFuturos'=>$eventosFuturos
                ], $vars);

            Response:: renderView (
                'index',
                $vars

            );

        }
        catch (Exception $exception) {
            throw $exception;
        }

    }

    public function eventosPortadaUsuario($id){

        try{
            $usr = App::get('database')->find(
                'usuarios',
                'Usuario',
                $id
            );

            if(!$usr)
                App::get('router')->redirect('404');


            $eventosUsuario = App::get('database')->findBy(
                'eventos',
                'Evento',
                [
                    'idCreador' => $id
                ]
            );
        }catch (Exception $exception){
            throw $exception;
        }

        Response:: renderView (
            'index',
            [
                'usr'=>$usr,
                'eventosUsuario'=>$eventosUsuario
            ]
        );
    }

    public function lista($id = null)
    {
        try{


            $filtros = [];
            $vars = [];


            // Id
            if($id != null) {
                $usr = App::get('database')->find('usuarios','Usuario',$id);
                $vars = array_merge($vars, [ 'usr' => $usr ]);
                $filtros = array_merge($filtros, [ "idCreador" => $id ]);
            }


            // asigna el patron de busqueda si el maininput no esta vacio
            if(isset($_POST['patronbusqueda']) && !empty($_POST['patronbusqueda'])){
                $filtroOR = [];

                if($id == null){
                    $creador = App::get('database')->findOneBy('usuarios', 'Usuario', [ 'nombre' => $_POST['patronbusqueda']], true, false);
                    if($creador != null)
                        $filtroOR = array_merge($filtroOR, ['idCreador' => $creador->getId()]);
                }

                $filtroOR = array_merge($filtroOR, [
                    'ENombre' => $_POST['patronbusqueda'],
                    'EDir' => $_POST['patronbusqueda'],
                    'EDesc' => $_POST['patronbusqueda']
                ]);

                $eventosOR = App::get('database')->findBy('eventos','Evento',$filtroOR , true, true);

                $vars = array_merge($vars, [ 'patronbusqueda' => $_POST['patronbusqueda']]);
            }


            // provincia
            if(isset($_POST['provinciabusqueda']) && !empty($_POST['provinciabusqueda'])){
                $filtros = array_merge($filtros, [
                    'EProvincia' => $_POST['provinciabusqueda']
                ]);
                $vars = array_merge($vars, [ 'provinciabusqueda' => $_POST['provinciabusqueda']]);
            }


            // categoria
            if(isset($_POST['categoriabusqueda']) && !empty($_POST['categoriabusqueda'])){
                $filtros = array_merge($filtros, [
                    'ECategoria' => $_POST['categoriabusqueda']
                ]);
                $vars = array_merge($vars, [ 'categoriabusqueda' => $_POST['categoriabusqueda']]);
            }

            $events = App::get('database')->findBy(
                'eventos',
                'Evento',
                $filtros,
                true,
                false
            );


            // filtra por nombre,descripcion y creador.
            if(isset($eventosOR)){
                if(count($eventosOR) != 0){
                    $reformatedevents = [];
                    foreach ($eventosOR as $eventoOr){
                        foreach ($events as $event){
                            if($eventoOr->getId() == $event->getId()){
                                $reformatedevents[] = $event;
                            }
                        }
                    }
                    $events = $reformatedevents;
                }else
                    $events = [];
            }


            // fehchabusqueda
            if(isset($_POST['fechabusqueda']) && !empty($_POST['fechabusqueda']) || isset($_POST['fechabusqueda2']) && !empty($_POST['fechabusqueda2'])){

                if(isset($_POST['fechabusqueda']) && !empty($_POST['fechabusqueda'] && !isset($_POST['fechabusqueda2']) || empty($_POST['fechabusqueda2']))){
                    $vars = array_merge($vars, [ 'fechabusqueda' => $_POST['fechabusqueda']]);
                    $eventsToFilter = $events;
                    $events = [];

                    foreach ($eventsToFilter as $event){
                        if($event->getFEvento() == $_POST['fechabusqueda'])
                            $events[] = $event;
                    }
                }elseif(isset($_POST['fechabusqueda2']) && !empty($_POST['fechabusqueda2']) && !isset($_POST['fechabusqueda']) || empty($_POST['fechabusqueda'])){
                    $vars = array_merge($vars, [ 'fechabusqueda2' => $_POST['fechabusqueda2']]);
                    $eventsToFilter = $events;
                    $events = [];

                    foreach ($eventsToFilter as $event){
                        if($event->getFEvento() == $_POST['fechabusqueda2'])
                            $events[] = $event;
                    }
                }elseif(isset($_POST['fechabusqueda']) && !empty($_POST['fechabusqueda']) || isset($_POST['fechabusqueda2']) && !empty($_POST['fechabusqueda2'])){
                    $vars = array_merge($vars, [ 'fechabusqueda' => $_POST['fechabusqueda']]);
                    $vars = array_merge($vars, [ 'fechabusqueda2' => $_POST['fechabusqueda2']]);

                    $eventsToFilter = $events;
                    $events = [];

                    foreach ($eventsToFilter as $event){
                        if(strtotime($event->getFEvento()) >= strtotime($_POST['fechabusqueda']) && strtotime($event->getFEvento()) <= strtotime($_POST['fechabusqueda2']))
                            $events[] = $event;
                    }
                }

                $usoRangoFechas = true;
            }


            // fecharangobusqueda
            if(isset($_POST['fecharangobusqueda']) && !empty($_POST['fecharangobusqueda']) && !isset($usoRangoFechas)){
                $esteLunes = strtotime("last monday");
                $manyana = strtotime( date("y-m-d", time()) . "+1 days");
                $esteSabado = strtotime(date("Y-m-d",$esteLunes) . " +5 days");
                $esteDomingo = strtotime(date("Y-m-d",$esteLunes) . " +6 days");
                $esteMesInicio = strtotime(date('m-01-Y', strtotime('this month')));

                // El puñetero strtotime no le gusta que existan "formatos absolutos" como en este caso el "t" del format, así que  habrá que guardar el datetime y convertir a date la fecha del evento.
                $esteMesFInal = date('m-t-Y', strtotime('this month'));


                $eventsToFilter = $events;
                $events = [];


                switch ($_POST['fecharangobusqueda']){
                    case 'ma':
                        // mañana.
                        foreach ($eventsToFilter as $event){
                            if(strtotime($event->getFEvento()) == $manyana)
                                $events[] = $event;
                        }
                        break;

                    case 'se':
                        // esta semana.
                        foreach ($eventsToFilter as $event){
                            if(strtotime($event->getFEvento()) >= $esteLunes && strtotime($event->getFEvento()) <= $esteDomingo)
                                $events[] = $event;
                        }

                        break;

                    case 'we':
                        // fin de semana.
                        foreach ($eventsToFilter as $event){
                            if(strtotime($event->getFEvento()) == $esteSabado || strtotime($event->getFEvento()) == $esteDomingo)
                                $events[] = $event;
                        }


                        break;

                    case 'me':
                        // este mes.
                        foreach ($eventsToFilter as $event){
                            if(strtotime($event->getFEvento()) >= $esteMesInicio && date('m-d-Y', strtotime($event->getFEvento())) <= $esteMesFInal)
                                $events[] = $event;
                        }

                        break;
                }


                $vars = array_merge($vars, [ 'fecharangobusqueda' => $_POST['fecharangobusqueda']]);
            }



            // filtra por eventos disponibles (a la venta)
            if(isset($_POST['disponibles']) && !empty($_POST['disponibles'])){
                $eventsToFilter = $events;
                $events = [];

                foreach ($eventsToFilter as $event){
                    if($event->getNER() > 0 && strtotime($event->getFVFinal()) >= time())
                        $events[] =  $event;
                }
                $vars = array_merge($vars, [ 'disponibles' => $_POST['disponibles']]);
            }


            // ordena la lista
            if(isset($_POST['ordenadobusqueda']) && !empty($_POST['ordenadobusqueda']) && count($events) != 0) {
                usort($events, array($this,  'separator'));
                $ordenado = $_POST['ordenadobusqueda'];
                $vars = array_merge($vars, ['ordenadobusqueda' => $ordenado]);
            }

            $vars = array_merge($vars, ['events' => $events]);

        }catch (Exception $exception){
            throw  $exception;
        }

        Response::renderView('eventslisted',
            $vars
        );
    }


    private function separator($a, $b)
    {
        switch ($_POST['ordenadobusqueda'])
        {
            case "EFecha":
                return strtotime($a->getFEvento()) - strtotime($b->getFEvento());
                break;
            case "ECategoria":
                return strcmp($a->getECategoria(), $b->getECategoria());
                break;
            case "EProvincia":
                return strcmp($a->getEProvincia(), $b->getEProvincia());
                break;
        }
    }


    public function detalleEvento($id){
        try{
            $event = App::get('database')->find('eventos','Evento',$id);
            $eventcreator = App::get('database')->find('usuarios','Usuario',$event->getIdCreador());

            Response:: renderView (
                'eventdetails',
                [
                    'event' => $event,
                    'eventcreator' => $eventcreator
                ]
            );

        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    private function setPosterRestrictions(Evento $event){
        $event->setDirUpload('uploads');
        $event->setNombreCampoFile('EPoster');
        $event->setRequiereThumbnail(true);
        $event->setPesolimite(1024000);
        $event->setTiposPermitidos(
            [
                'image/jpg',
                'image/png',
                'image/jpeg'
            ]);
    }

    private function gestPoster(Evento $event)
    {
        try
        {
            $event->setEPoster($event->subeImagen($event->getEPoster()));
        }
        catch (UploadException $uploadException)
        {
            if ($uploadException->getFileError() === UPLOAD_ERR_NO_FILE)
            {
                if($event->getEPoster() == null || $event->getEPoster() == "")
                    $event->setEPoster('serverimg/defaultposter.png');
            }
            else
            {
                throw $uploadException;
            }
        }
    }

    private function save(Evento $event)
    {
        try
        {
            $parameters = [
                'ENombre' => $event->getENombre(),
                'idCreador' => $event->getIdCreador(),
                'EDesc' => $event->getEDesc(),
                'ECategoria' => $event->getECategoria(),
                'EPoster' => $event->getEPoster(),
                'EExterno' => $event->getEExterno(),
                'EProvincia' => $event->getEProvincia(),
                'EDir' => $event->getEDir(),
                'FVInicio' => $event->getFVInicio(),
                'FVFinal' => $event->getFVFinal(),
                'FEvento' => $event->getFEvento(),
                'NET' => $event->getNET(),
                'NER' => $event->getNER(),
                'PEntrada' => $event->getPEntrada()
            ];

            if (is_null($event->getId()) && $this->checkEventIntegrity($event)) {

                App::get('database')->insert('eventos', $parameters);
                unset($parameters['PEntrada']);
                $eventCreated = App::get('database')->findOneBy('eventos','Evento', $parameters);
                App::get('router')->redirect('evento/' . $eventCreated->getId() . '/detalles');

            }else {

                $eventoDB = App::get('database')->find('eventos', 'Evento', $event->getId());

                if(!$this->checkEventIntegrity($eventoDB))
                    return false;

                $filters = [
                    'id' => $event->getId()
                ];

                App::get('database')->update('eventos', $parameters, $filters);
                App::get('router')->redirect('evento/' . $eventoDB->getIdCreador() . '/listado');
            }
        }
        catch(Exception $exception)
        {
            throw $exception;
        }
    }

    private function validate(Evento $event)
    {

        $formNames = ['ENombre','EDesc','ECategoria','EProvincia', 'EDir', 'FVInicio', 'FVFinal', 'FEvento', 'NET'];

        foreach ($formNames as $formName) {
            if(!isset($_POST[$formName]) || empty($_POST[$formName])){
                $_SESSION['error_messages'][] = 'No puedes dejar ningun campo vacio';
                break;
            }
        }

        if(isset($_POST['FVInicio']) && !empty($_POST['FVInicio']) &&
            isset($_POST['FVFinal']) && !empty($_POST['FVFinal']) &&
            isset($_POST['FEvento']) && !empty($_POST['FEvento']))
        {
            if(!(strtotime($_POST['FVInicio']) <= strtotime($_POST['FVFinal'])))
                $_SESSION['error_messages'][] = 'La fecha de inicio de venta no puede ser mayor que la fecha de fin de ventas.';

            if(!(strtotime($_POST['FVFinal']) <= strtotime($_POST['FEvento'])))
                $_SESSION['error_messages'][] = 'La fecha de fin de ventas no puede ser mayor que la fecha del propio evento.';
        }


        if($event->getNER() != null && intval($event->getNET()) - intval($event->getNER()) > intval($_POST['NET']))
            $_SESSION['error_messages'][] = 'Ya se han comprado entradas, el nuevo numero total de entradas no puede ser menor al numero vendido.';


        $this->setPosterRestrictions($event);
        if($_FILES[$event->getNombreCampoFile()]['error'] != UPLOAD_ERR_NO_FILE)
        {
            $event->compruebaPeso();
            $event->compruebaTipo();
            $event->compruebaConflictoThumbnail();
        }


        if(count($_SESSION['error_messages']) != 0) {

            $vars = [];

            if(!is_null($event->getId())) {
                $vars = ['event' => $event];
            }

            Response:: renderView(
                'newevent',
                $vars
            );
            return false;
        }
        return true;
    }

    public function create()
    {
        $event = new Evento();

        if ($this->validate($event)) {

            $event->setIdCreador(App::get('user')->getId());
            $event->setENombre($_POST['ENombre']);
            $event->setEDesc($_POST['EDesc']);
            $event->setECategoria($_POST['ECategoria']);

            if(isset($_POST['EExterno']) && !empty($_POST['EExterno']))
                $event->setEExterno($_POST['EExterno']);
            else
                $event->setEExterno('');

            $event->setEProvincia($_POST['EProvincia']);
            $event->setEDir($_POST['EDir']);
            $event->setFVInicio($_POST['FVInicio']);
            $event->setFVFinal($_POST['FVFinal']);
            $event->setFEvento($_POST['FEvento']);
            $event->setNET($_POST['NET']);
            $event->setNER($event->getNET());
            $event->setPEntrada(floatval(str_replace(',', '.', $_POST['PEntrada'])));

            $this->gestPoster($event);
            $this->save($event);
        }
    }

    public function update($id)
    {
        try {
            $event = App::get('database')->find('eventos', 'Evento', $id);
        } catch (Exception $exception) {
            throw $exception;
        }

        if ($this->checkEventIntegrity($event) && $this->validate($event)) {
            $event->setENombre($_POST['ENombre']);
            $event->setEDesc($_POST['EDesc']);
            $event->setECategoria($_POST['ECategoria']);

            if (isset($_POST['EExterno']) && !empty($_POST['EExterno']))
                $event->setEExterno($_POST['EExterno']);
            else
                $event->setEExterno("");

            $event->setEProvincia($_POST['EProvincia']);
            $event->setEDir($_POST['EDir']);
            $event->setFVInicio($_POST['FVInicio']);
            $event->setFVFinal($_POST['FVFinal']);
            $event->setFEvento($_POST['FEvento']);

            if($event->getNER() == $event->getNET()){
                $event->setNET($_POST['NET']);
                $event->setNER($_POST['NET']);
            }
            else{
                $event->setNER(strval(((intval($_POST['NET']) - intval($event->getNET())) + intval($event->getNER()))));
                $event->setNET($_POST['NET']);
            }

            $event->setPEntrada(floatval(str_replace(',', '.', $_POST['PEntrada'])));

            $this->gestPoster($event);
            $this->save($event);
        }
    }

    public function submitEvent($id = null)
    {
        $vars = [];

        if($id != null){

            $event = App::get('database')->find('eventos', 'Evento', $id);

            if($this->checkEventIntegrity($event))
                $vars = ['event' => $event];
            else
                return false;

        }

        Response:: renderView (
            'newevent',
            $vars
        );
    }

    public function delete($id)
    {
        try {

            $event = App::get('database')->find('eventos', 'Evento', $id);

            if ($this->checkEventIntegrity($event)) {

                $filters = [
                    'id' => $id,
                ];

                if (strpos($event->getEPoster(), 'uploads') !== false)
                    unlink($event->getEPoster());

                App::get('database')->delete('eventos', $filters);
                App::get('router')->redirect('evento/listado');
            }else
                return false;
        }
        catch(Exception $exception)
        {
            throw $exception;
        }
    }

    public function checkEventIntegrity($event)
    {
        if($event == null){
            App::get('router')->redirect('404');
            return false;
        }elseif ($event->getIdCreador() != App::get('user')->getId() && !Security::isUserGranted('ROLE_ADMINISTRADOR')) {
            App::get('router')->redirect('403');
            return false;

        } elseif ($event->getNET() != $event->getNER() && !Security::isUserGranted('ROLE_ADMINISTRADOR')) {
            App::get('router')->redirect('409');
            return false;
        }

        return true;
    }
}