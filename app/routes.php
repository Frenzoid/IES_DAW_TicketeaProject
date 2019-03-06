<?php
/**
 * @var \liveticket\core\Router $router
 */

// Acabados en clase. - Mejorados el 23/12/2017
$router->get(   'idiomas/es',           'IdiomaController@idiomaEspanol');
$router->get(   'idiomas/en',           'IdiomaController@idiomaIngles');

// Acabados el día de la agenda
$router->get(   'login',                'AuthController@login');
$router->get(   'logout',               'AuthController@logout');
$router->get(   '403',                  'AuthController@unauthorized');
$router->get(   '404',                  'PagesController@notFound');
$router->get(   '409',                  'PagesController@conflict');
$router->post(  'check-login',          'AuthController@checkLogin');

// Acabados el 21/12/2017
$router->get(   'gencaptcha',           'CaptchaController@showCaptcha');

// Acabado el 27/12/2017
$router->post(  'tramites/:id/tramitar','TramitesController@tramitar',          'ROLE_COMPRADOR');
$router->get(   'tramites',            'TramitesController@mostrarTickets',      'ROLE_COMPRADOR');
$router->get(   'tramites/:id/tickets','TramitesController@mostrarTickets',      'ROLE_ADMINISTRADOR');

// Acabado el 29/12/2017
$router->get(   'mensajes',             'MensajesController@mostrarChat',         'ROLE_COMPRADOR');
$router->get(   'mensajes/:id',         'MensajesController@mostrarChat',         'ROLE_COMPRADOR');
$router->post(  'mensajes/:id/enviar',  'MensajesController@mandarMensaje',       'ROLE_COMPRADOR');

// Acabado el 26/12/2017
$router->get(   '',                     'EventosController@eventosPortada');
$router->post(  '',                     'EventosController@eventosPortada');
$router->get(   ':id',                  'EventosController@eventosPortadaUsuario');
$router->get(   'evento/listado',       'EventosController@lista');
$router->post(  'evento/listado',       'EventosController@lista');
$router->get(   'evento/:id/listado',   'EventosController@lista');
$router->post(  'evento/:id/listado',   'EventosController@lista');
$router->get(   'evento/:id/detalles',  'EventosController@detalleEvento');
$router->get(   'evento/registrar',     'EventosController@registrar',            'ROLE_GESTOR');
$router->get(   'evento/nuevoevento',   'EventosController@submitEvent',          'ROLE_GESTOR');
$router->get(   'evento/:id/edit',      'EventosController@submitEvent',          'ROLE_GESTOR');
$router->get(   'evento/:id/delete',    'EventosController@delete',               'ROLE_GESTOR');
$router->post(  'evento/:id/update',    'EventosController@update',               'ROLE_GESTOR');
$router->post(  'evento/create',        'EventosController@create',               'ROLE_GESTOR');

// Acabados el 24/12/2017
$router->get(   'usuario/registrarse',  'UsuariosController@registrar');
$router->post(  'usuario/create',       'UsuariosController@create');
$router->get(   'usuario/listados',     'UsuariosController@lista',              'ROLE_COMPRADOR');
$router->post(  'usuario/listados',     'UsuariosController@lista',              'ROLE_COMPRADOR');
$router->get(   'usuario/perfil',       'UsuariosController@miperfil',           'ROLE_COMPRADOR');
$router->get(   'usuario/:id/edit',     'UsuariosController@edit',               'ROLE_COMPRADOR');
$router->post(  'usuario/:id/update',   'UsuariosController@update',             'ROLE_COMPRADOR');
$router->get(   'usuario/:id/perfil',   'UsuariosController@perfil',             'ROLE_COMPRADOR');
$router->get(   'usuario/:id/delete',   'UsuariosController@delete',             'ROLE_ADMINISTRADOR');
