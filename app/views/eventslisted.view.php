<h1 class="secTitle">
    <?= _("Eventos:") ?>
</h1>
<p class="navigator">
    <a href="/">Index</a>>
    <a href="/evento/<?php if(isset($usr)) echo"{$usr->getId()}/"; ?>listado"><?= _("Eventos Listado") ?></a>
</p>
<?php if(isset($usr)) : ?>
    <div class="busqueda">
        <label for="ordenado"><?= _("Buscando en eventos de ") ?> <?=$usr->getNombre(); ?></label>
        <div>
            <a class="button" href="/evento/listado"><?= _("Buscar globalmente") ?></a>
        </div>
    </div>
<? endif; ?>
<section class="mainSections force-flex-col">

    <?php foreach ($events as $event): ?>
        <article class="list-item">

            <figure class="list-event-image_caption">
                    <img src="/<?php if(strpos($event->getEPoster(),'serverimg') !== false) echo $event->getEPoster();  else echo substr_replace( $event->getEPoster(),'/thumbnail_', strpos($event->getEPoster(),'/'), 1); ?>" alt="Event Name">
                    <figcaption><a href="/evento/<?= $event->getId(); ?>/detalles"><?= $event->getENombre();?></a></figcaption>
            </figure>

            <div class="list-item-info">
                <div>
                    <i class="fa fa-calendar"></i>
                    <time datetime="<?= $event->getFEvento();?>"><?= $event->getFEvento();?></time>
                    <p><?= _("Fech. Evento:") ?> </p>
                </div>
                <div>
                    <i>&euro;</i>
                    <div><?= $event->getPEntrada();?> </div>
                    <p><?= _("Precio x entrada:") ?> </p>
                </div>
            </div>
            <div class="list-item-info">
                <div>
                    <i class="fa fa-map-marker"></i>
                    <div><?= $event->getEProvincia();?></div>
                    <p><?= _("Provincia:") ?> </p>
                </div>
                <div>
                    <i class="fa fa-calendar"></i>
                    <div><?= $event->getECategoria();?></div>
                    <p><?= _("Categoria:") ?> </p>
                </div>
            </div>

            <div class="list-item-actions">
                <a href="/evento/<?= $event->getId(); ?>/detalles"><?= _("Ver") ?></a>
                <?php if(isset($usr) && ($event->getIdCreador() == $usr->getId() && $event->getNET() == $event->getNER()) || \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')): ?>
                    <a href="/evento/<?= $event->getId(); ?>/edit"><?= _("Editar") ?></a>
                    <a href="/evento/<?= $event->getId(); ?>/delete"><?= _("Borrar") ?></a>
                <? endif; ?>
            </div>
        </article>
    <? endforeach; ?>

</section>