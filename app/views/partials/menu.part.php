<aside class="sideNavHidden" id="sideNav">
<?php if($usuario != null) : ?>
    <?php if(liveticket\core\Security::isUserGranted('ROLE_COMPRADOR')) :?>
        <h2> <?= $usuario->getNombre(); ?> </h2>
        <a href="/usuario/perfil"><?= _("Mi perfil") ?></a>
        <a href="/tramites"><?= _("Mis entradas") ?></a>
        <a href="/mensajes"><?= _("Mis mensajes") ?></a>
    <? endif;?>

    <?php if(liveticket\core\Security::isUserGranted('ROLE_GESTOR')) :?>
        <h2> <?= _("Eventos") ?> </h2>
        <a href="/evento/<?= $usuario->getId(); ?>/listado"><?= _("Mis eventos") ?></a>
        <a href="/evento/nuevoevento"><?= _("Crear Evento") ?></a>
    <? endif;?>

<? endif; ?>
    <h2> <?= _("Pagina") ?> </h2>
    <?php if(liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')) echo '<a href="/usuario/registrarse">' . _("Crear Cuenta") . '</a>';?>
    <?php if($usuario === null) echo '<a href="/usuario/registrarse">' . _("Registrarse") . '</a>';?>
    <?php if(liveticket\core\Security::isUserGranted('ROLE_COMPRADOR')) echo '<a href="/usuario/listados">' . _("Lista de Usuarios") . '</a>';?>
    <?php if($usuario === null) echo '<a class="auth">' . _("Acceder") . '</a>';?>
    <!-- a href="#">Acerca de</a-->
    <?php if($usuario !== null) echo '<a href="/logout">'  . _("Salir de ") . $usuario->getNombre() . '</a>'?>
    <div class="lang-holder">
        <a <?php if($_SESSION['language'] == "es_ES.utf8") echo "class='asideActive'"?> href="/idiomas/es"><img src="/serverimg/spanish_flag.png" alt="/serverimg/spanish_flag.png"></a>
        <a <?php if($_SESSION['language'] == "en_GB.utf8") echo "class='asideActive'"?> href="/idiomas/en"><img src="/serverimg/english_flag.png" alt="/serverimg/english_flag.png"></a>
    </div>
</aside>