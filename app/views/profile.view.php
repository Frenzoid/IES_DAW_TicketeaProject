<?php use liveticket\core\App;

if(isset($usr))
        $profileUser = $usr;
    else
        $profileUser = $usuario;
?>


<h1 class="secTitle ">
    <?= $profileUser->getNombre(); ?>
</h1>
<p class="navigator">
    <a href="">Index</a>
    >
    <?php if(isset($usr)) :?>
        <a href="usuario/perfil"><?= $profileUser->getNombre(); ?></a>
    <?php else:?>
        <a href="usuario/<?= $profileUser->getId(); ?>/perfil"><?= $profileUser->getNombre(); ?></a>
    <?endif; ?>
</p>
<section class="mainSections ">
    <article id="profile">
        <div id="profile-top">
            <div>
                <figure>
                    <img src="/<?= $profileUser->getAvatar(); ?>">
                </figure>

            </div>
            <ul>
                <li><a><?= _("Nombre") ?>: <?= $profileUser->getNombre(); ?></a></li>
                <li><a><?= _("Email") ?>: <?= $profileUser->getEmail(); ?></a></li>
                <li><a><?= _("Rol") ?>: <?= $profileUser->getRole(); ?></a></li>
            </ul>
            <ul>
                <li>
                    <a href="/evento/<?= $profileUser->getId(); ?>/listado"> <?= _("Ver eventos listados") ?></a>
                </li>
                <li>
                    <a href="/<?= $profileUser->getId(); ?>"> <?= _("Ver eventos en portada") ?></a>
                </li>
                <?php if(App::get('user')->getId() != $profileUser->getId()): ?>
                    <li>
                        <a href="/mensajes/<?= $profileUser->getId(); ?>"> <?= _("Mandar mensaje") ?> </a>
                    </li>
                <? endif; ?>
                <?php if(\liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR') && $profileUser->getId() != $usuario->getId()): ?>
                    <li>
                        <a href="/tramites/<?= $profileUser->getId(); ?>/tickets"> <?= _("Ver entradas de") ?> <?= $profileUser->getNombre(); ?></a>
                    </li>
                <? endif; ?>
                <?php if(App::get('user')->getId() === $profileUser->getId() ) :?>
                    <li>
                        <a href="/tramites"> <?= _("Ver mis entradas") ?> </a>
                    </li>
                    <li>
                        <a href="/mensajes"> <?= _("Ver mis mensajes") ?> </a>
                    </li>

                <? endif; ?>
            </ul>
        </div>
        <?php if(App::get('user')->getId() === $profileUser->getId() || \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')): ?>
        <div>
            <div><h4><?= _("Eventos totales") ?>: <?= $userProfit->totevents ?> </h4></div>
            <div><h4><?= _("Tickets vendidos") ?>: <?= $userProfit->tottickets ?> </h4></div>
            <div><h4><?= _("Ganancias totales") ?>: <?= $userProfit->totbenefits ?>&euro;</h4></div>
        </div>
        <div id="profile-bot">
            <a href="/usuario/<?= $profileUser->getId(); ?>/edit"><?= _("Editar perfil") ?></a>
        </div>
        <? endif; ?>
    </article>
</section>