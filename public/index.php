<?php

require '../vendor/autoload.php';

use liveticket\core\App;
use liveticket\core\Request;
use liveticket\core\Router;
use liveticket\core\Translator;

require '../core/bootstrap.php';

if(isset($_SESSION['language']) === true){
    $language = $_SESSION['language'];

}else
{
    $language = current(array_keys(Translator::dameArrayDeIdiomasOrdenado()));
    $_SESSION['language'] = $language;
}

putenv("LC_ALL=$language");
setlocale(LC_ALL, $language);

bindtextdomain("en_GB", "../locale");
bind_textdomain_codeset("en_GB", "UTF-8");
textdomain("en_GB");

$log = new Monolog\Logger($config['logs']['name']);
$log->pushHandler(
    new Monolog\Handler\StreamHandler(
        $config['logs']['file'],
        Monolog\Logger:: WARNING )
);

if (isset($_SESSION['usuario']))
{
    $usuario = App:: get ('database')->find(
        'usuarios', 'Usuario', $_SESSION['usuario']);
}
else
    $usuario = null;

App:: bind ('user', $usuario);


try
{
    Router::load(__DIR__ . '/../app/routes.php');
    App::get('router')->direct(Request::uri(), Request::method());
}
catch(Exception $ex)
{
    $log->addError($ex->getMessage());
}
