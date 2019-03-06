<?php
namespace liveticket\app\controllers;

use liveticket\app\exceptions\UploadException;
use liveticket\core\App;
use liveticket\core\Response;
use liveticket\app\entities\Usuario;
use liveticket\core\Security;
use Exception;
use stdClass;

class UsuariosController
{

    public function lista()
    {
        try{

            $filtros = [];
            $patron = "";
            $ordenado = "";

            if(isset($_POST['patron']) && !empty($_POST['patron'])){
                $filtros = [
                    'nombre' => $_POST['patron'],
                    'provincia' => $_POST['patron'],
                    'fechaAlta' => $_POST['patron'],
                    'role' => $_POST['patron'],
                ];
                $patron = $_POST['patron'];
            }


            $usuarios = App::get('database')->findBy(
                'usuarios',
                'Usuario',
                $filtros,
                true,
                true
            );

            foreach ($usuarios as $usuario){
                $this->isUserDeletable($usuario);
                $usuario->eventUd = $this->getUserBenefits($usuario->getId())->totevents;
            }

            if(isset($_POST['ordenado']) && !empty($_POST['ordenado'])) {
                usort($usuarios, array($this,  'separator'));
                $ordenado = $_POST['ordenado'];
            }

        }catch (Exception $exception){
            throw  $exception;
        }

        Response::renderView('userslisted',[
            "users" => $usuarios,
            'patron' => $patron,
            'ordenado' => $ordenado
        ]);
    }

    public function separator($a, $b)
    {
        switch ($_POST['ordenado'])
        {
            case "FechaAlta":
                return strtotime($a->getFechaAlta()) - strtotime($b->getFechaAlta());
                break;
            case "Role":
                return strcmp($a->getRole(), $b->getRole());
                break;
            case "Provincia":
                return strcmp($a->getProvincia(), $b->getProvincia());
                break;
        }
    }

    public function miperfil(){
        Response:: renderView(
            'profile',[
                'userProfit' => $this->getUserBenefits($_SESSION['usuario'])
            ]
        );
    }

    public function perfil($id){
        try {

            $usr = App::get('database')->findOneBy('usuarios', 'Usuario', ['id' => $id]);
            if ($usr == null) {
                App::get('router')->redirect('404');
                return false;
            }


            Response:: renderView(
                'profile',
                [
                    'usr' => $usr,
                    'userProfit' => $this->getUserBenefits($usr->getId())
                ]
            );

        }catch (Exception $exception)
        {
            throw $exception;
        }
    }

    private function setAvatarRestrictions(Usuario $usuario){
        $usuario->setDirUpload('uploads');
        $usuario->setNombreCampoFile('avatar');
        $usuario->setNuevoAncho(100);
        $usuario->setPesolimite(10240);
        $usuario->setTiposPermitidos(
            [
                'image/jpg',
                'image/png',
                'image/jpeg'
            ]);
    }

    private function gestAvatar(Usuario $usuario)
    {
        try
        {
            $usuario->setAvatar($usuario->subeImagen($usuario->getAvatar()));
        }
        catch (UploadException $uploadException)
        {
            if ($uploadException->getFileError() === UPLOAD_ERR_NO_FILE)
            {
                if($usuario->getAvatar() == null || $usuario->getAvatar() == "")
                    $usuario->setAvatar('serverimg/defaultavatar.jpg');
            }
            else
            {
                throw $uploadException;
            }
        }
    }

    private function save(Usuario $usuario)
    {
        try
        {

            if(!$this->checkUserIntegrity($usuario))
                return false;

            $parameters = [
                'nombre' => $usuario->getNombre(),
                'passwd' => $usuario->getPasswd(),
                'email' => $usuario->getEmail(),
                'salt' => $usuario->getSalt(),
                'role' => $usuario->getRole(),
                'provincia' => $usuario->getProvincia(),
                'avatar' => $usuario->getAvatar()
            ];

            if (is_null($usuario->getId()) && !Security::isUserGranted('ROLE_COMPRADOR')) {

                App::get('database')->insert('usuarios', $parameters);
                $usrCreated = App::get('database')->findOneBy('usuarios','Usuario', $parameters);
                $_SESSION['usuario'] = $usrCreated->getId();
                App::get('router')->redirect('usuario/perfil');

            }
            elseif(is_null($usuario->getId()) && Security::isUserGranted("ROLE_ADMINISTRADOR")){
                $parameters = array_merge($parameters, ['idCreador' => App::get('user')->getId()]);
                App::get('database')->insert('usuarios', $parameters);
                App::get('router')->redirect('usuario/listados');
            }else {
                if(Security::isUserGranted('ROLE_ADMINISTRADOR') || (!is_null($usuario->getId()) && $usuario->getId() == App::get('user')->getId()))
                {
                    $filters = [
                        'id' => $usuario->getId()
                    ];

                    App::get('database')->update('usuarios', $parameters, $filters);

                    if(Security::isUserGranted('ROLE_ADMINISTRADOR'))
                        App::get('router')->redirect('usuario/listados');
                    else
                        App::get('router')->redirect('usuario/perfil');
                }else
                    App::get('router')->redirect('403');

            }
        }
        catch(\PDOException $pdoException)
        {
            if ($pdoException->getCode() === '23000')
            {
                $_SESSION['error_messages'][] = 'Ya existe un el mismo nombre de usuario ' . $usuario->getNombre();
                App::get('router')->redirect('usuario/registrarse');
            }
        }
        catch(Exception $exception)
        {
            throw $exception;
        }
    }

    private function validate(Usuario $user)
    {
        CaptchaController::checkCaptcha();

        $formNames = ['nombre', 'passwd', 'email', 'provincia' ];

        foreach ($formNames as $formName) {
            if(!isset($_POST[$formName]) || empty($_POST[$formName])){
                $_SESSION['error_messages'][] = 'No puedes dejar ningun campo vacio';
                break;
            }
        }

        if(!Security::isUserGranted('ROLE_ADMINISTRADOR') &&
        isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['email2']) && !empty($_POST['email2']) &&
        $_POST['email2'] != $_POST['email'])
            $_SESSION['error_messages'][] = 'Los emails no coinciden';

        if(strpos($_POST['email'], '@') === false || strpos($_POST['email'], '.') == false)
            $_SESSION['error_messages'][] = 'El email no tiene el formato adecuado';

        if(!Security::isUserGranted('ROLE_ADMINISTRADOR') &&
        isset($_POST['passwd']) && !empty($_POST['passwd']) &&
        isset($_POST['passwd2']) && !empty($_POST['passwd2']) &&
        $_POST['passwd2'] != $_POST['passwd'])
            $_SESSION['error_messages'][] = 'Las contraseñas no coinciden';


        $this->setAvatarRestrictions($user);
        if($_FILES[$user->getNombreCampoFile()]['error'] != UPLOAD_ERR_NO_FILE)
        {
            $user->compruebaPeso();
            $user->compruebaTipo();
        }

        $dupeduser = null;

        try{
            $dupeduser = App::get('database')->findOneBy(
                'usuarios',
                'Usuario',
                [
                    'nombre' => $_POST['nombre']
                ]
            );
        }
        catch (Exception $exception){
            throw $exception;
        }


        if(count($_SESSION['error_messages']) != 0) {

            $vars = [];

            if(!is_null($user->getId())) {
                $vars = ['user' => $user];
                if($dupeduser != null && $dupeduser->getId() != $user->getId())
                    $_SESSION['error_messages'][] = 'El nombre ya está en uso.';

            }else{
                if($dupeduser != null)
                    $_SESSION['error_messages'][] = 'El nombre ya está en uso.';
            }

            Response:: renderView(
        'register',
                $vars
            );
            return false;
        }
        return true;
    }

    public function create()
    {
        $user = new Usuario();
        if ($this->validate($user)) {

            $salt = Security::getSalt();
            $password = Security::encrypt($_POST['passwd'], $salt);

            $user->setNombre($_POST['nombre']);
            $user->setProvincia($_POST['provincia']);
            $user->setEmail($_POST['email']);
            $user->setPasswd($password);
            $user->setSalt($salt);
            if(isset($_POST['role']) && !empty($_POST['role']))
                $user->setRole($_POST['role']);
            else
                $user->setRole('ROLE_COMPRADOR');
            $this->gestAvatar($user);
            $this->save($user);
        }
    }

    public function registrar()
    {
        if(!Security::isUserGranted('ROLE_ADMINISTRADOR') && Security::isUserGranted('ROLE_COMPRADOR'))
            App::get('router')->redirect('');
        else
            Response:: renderView ('register');
    }

    public function update($id)
    {

        if($id != App::get('user')->getId() && !Security::isUserGranted('ROLE_ADMINISTRADOR')){ // revisar esto
            App::get('router')->redirect('403');
            return false;
        }

        try{
            $user = App:: get ('database')->find('usuarios', 'Usuario', $id);
        }
        catch (Exception $exception) {
            throw $exception;
        }

        if($this->checkUserIntegrity($user) && $this->validate($user)) {
            $salt = Security::getSalt();
            $password = Security::encrypt($_POST['passwd'], $salt);

            $user->setNombre($_POST['nombre']);
            $user->setProvincia($_POST['provincia']);
            $user->setEmail($_POST['email']);
            $user->setPasswd($password);
            $user->setSalt($salt);
            if(Security::isUserGranted('ROLE_ADMINISTRADOR'))
                $user->setRole($_POST['role']);
            $this->gestAvatar($user);
            $this->save($user);
        }
    }

    public function edit($id)
    {
        if($id === App::get('user')->getId() || Security::isUserGranted('ROLE_ADMINISTRADOR'))
        {
            try{
                $user = App:: get ('database')->find('usuarios', 'Usuario', $id);
            }catch (Exception $exception){
                throw $exception;
            }

            if($this->checkUserIntegrity($user)){
                Response:: renderView (
                    'register',
                    [
                        'user'=>$user,
                    ]
                );
            }
        }
        else
        {
            App::get('router')->redirect('403');
        }
    }

    public function delete($id)
    {
        try{
            $filters = [
                'id' => $id,
            ];

            $usuario = App::get('database')->find('usuarios', 'Usuario', $id);

            $this->isUserDeletable($usuario);

            if($usuario->deletable)
            {

                if($usuario->getRole() != "ROLE_ADMINISTRADOR"){

                    if(strpos($usuario->getAvatar(), 'uploads') !== false)
                        unlink($usuario->getAvatar());

                    App::get('database')->delete('usuarios', $filters);
                    App::get('router')->redirect('usuario/listados');
                }else{
                    App::get('router')->redirect('403');
                }
            }else
                App::get('router')->redirect('409');
            return false;
        }
        catch(Exception $exception)
        {
            throw $exception;
        }
    }

    private function checkUserIntegrity($user){
        if($user == null){
            App::get('router')->redirect('404');
            return false;
        }

        return true;
    }

    private function isUserDeletable($usuario){
        $this->checkUserIntegrity($usuario);

        try{
            $eventos = App::get('database')->findOneBy(
                'eventos',
                'Evento',
                [ 'idCreador' => $usuario->getId()]
            );

            $facturas = App::get('database')->findOneBy(
                'facturas',
                'Factura',
                [ 'usuarioid' => $usuario->getId()]
            );

            $mensjes = App::get('database')->findOneBy(
                'mensajes',
                'Mensaje',
                [
                    'emisorid' => $usuario->getId(),
                    'receptorid' => $usuario->getId()
                ]
            );

        }catch (Exception $exception){
            throw $exception;
        }

        if(is_null($eventos) && is_null($facturas) && is_null($mensjes))
            $usuario->deletable = true;
        else
            $usuario->deletable = false;

        return count($eventos);
    }

    private function getUserBenefits($usrid){
        $userProfit = new stdClass();
        $userProfit->totbenefits = 0;
        $userProfit->tottickets = 0;
        $userProfit->totevents = 0;

        try{
            $userEvents = App::get('database')->findBy('eventos', 'Evento', ['idCreador' => $usrid]);
        }
        catch (Exception $exception){
            throw $exception;
        }

        foreach ($userEvents as $userEvent) {
            $userProfit->totbenefits += (intval($userEvent->getNET()) - intval($userEvent->getNER())) * floatval($userEvent->getPEntrada());
            $userProfit->tottickets += intval($userEvent->getNET()) - intval($userEvent->getNER());
        }
        $userProfit->totevents = count($userEvents);

        return $userProfit;
    }
}