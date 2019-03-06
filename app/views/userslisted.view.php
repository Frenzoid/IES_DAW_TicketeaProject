<h1 class="secTitle">
    Usuarios:
</h1>
<p class="navigator">
    <a href="/">Index</a>>
    <a href="/usuario/listados"><?= _("Usuario") ?></a>
</p>
<section class="mainSections force-flex-col">
<form class="busqueda userformdetect" id="busqueda" action="/usuario/listados" method="POST">
    <div>
        <label for="patron"><?= _("Buscar usuario") ?></label>
        <input type="text" name="patron" placeholder="Buscar..." value="<?php if(isset($patron)) echo "$patron"; ?>">
    </div>
    <div>
        <label for="ordenado"><?= _("ordenar por") ?>:</label>
        <select name="ordenado" id="detectchanges">
            <option value="" <?php if($ordenado == "") echo "selected"; ?> disabled></option>
            <option value="FechaAlta" <?php if($ordenado == "FechaAlta") echo "selected"; ?>><?= _("Fecha") ?></option>
            <option value="Role" <?php if($ordenado == "Role") echo "selected"; ?>><?= _("Rol") ?></option>
            <option value="Provincia" <?php if($ordenado == "Provincia") echo "selected"; ?>><?= _("Provincia") ?></option>
        </select>
    </div>
    <div>
        <input type="submit" value="buscar">
    </div>
</form>
    <?php foreach ($users as $user) :?>
        <article class="list-item">
            <figure class="list-user-image_caption">
                <img src="/<?= $user->getAvatar(); ?>" alt="user avatar">
                <figcaption><p><?= $user->getNombre(); ?></p></figcaption>
            </figure>
            <div class="list-item-info">
                <div>
                    <i class="fa fa-id-card" aria-hidden="true"></i>
                    <div><p></p><?= $user->getRole(); ?></div>
                    <p> <?= _("Nivel de autorizacion") ?>:</p>
                </div>
                <div>
                    <i class="fa fa-calendar"></i>
                    <time datetime="<?= $user->getFechaAlta(); ?>"><?= $user->getFechaAlta(); ?></time>
                    <p><?= _("fecha de alta") ?></p>
                </div>
            </div>
            <div class="list-item-info">
                <div>
                    <i class="fa fa-th-list"></i>
                    <div><p><?= $user->eventUd; ?></p></div>
                    <p> <?= _("Numero de eventos") ?></p>
                </div>
                <div>
                    <i class="fa fa-map-marker"></i>
                    <div><p><?= $user->getProvincia(); ?></p></div>
                    <p> <?= _("Provincia") ?></p>
                </div>
            </div>
            <div class="list-item-actions">
                <a href="/usuario/<?= $user->getid(); ?>/perfil"><?= _("Ver") ?></a>
                <?php if(\liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')) : ?>
                    <a href="/usuario/<?= $user->getId(); ?>/edit"><?= _("Editar") ?></a>
                    <?php if($user->deletable && $user->getRole() != "ROLE_ADMINISTRADOR") : ?>
                        <a href="/usuario/<?= $user->getId(); ?>/delete"><?= _("Borrar") ?></a>
                    <? endif; ?>
                <? endif; ?>
            </div>
        </article>
    <? endforeach; ?>

</section>