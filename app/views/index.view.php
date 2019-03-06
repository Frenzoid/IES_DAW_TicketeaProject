<?php if(!isset($usr)) : ?>

    <h1 class="secTitle "><?= _("Eventos Actuales") ?></h1>
    <p class="navigator"> <a href="/">Index</a> </p>
    <section class="mainSections ">

        <?php foreach($eventosDisponibles as $evento) : ?>

            <article class="event-card">
                <figure class="event-card-image_title">
                    <img src="/<?php if(strpos($evento->getEPoster(),'serverimg') !== false) echo $evento->getEPoster();  else echo substr_replace( $evento->getEPoster(),'/thumbnail_', strpos($evento->getEPoster(),'/'), 1); ?>" alt="Event Name">
                    <figcaption><?= $evento->getENombre(); ?></figcaption>
                </figure>
                <div class="event-card-info">
                    <div>
                        <div><?= $evento->getPEntrada(); ?> </div>
                        <i>&euro;</i>
                    </div>
                    <div>
                        <time datetime="<?= $evento->getFEvento(); ?>"><?= $evento->getFEvento(); ?></time>
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <div><?= $evento->getEProvincia(); ?></div>
                        <i class="fa fa-map-marker"></i>
                    </div>
                </div>
                <div class="event-card-actions">
                    <a href="/evento/<?= $evento->getId(); ?>/detalles"><?= _("Ver") ?></a>
                    <?php if($usuario != null && ($evento->getIdCreador() == $usuario->getId() && $evento->getNET() == $evento->getNER()) || \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')): ?>
                        <a href="/evento/<?= $evento->getId(); ?>/edit"><?= _("Editar") ?></a>
                        <a href="/evento/<?= $evento->getId(); ?>/delete"><?= _("Borrar") ?></a>
                    <? endif; ?>
                </div>
            </article>

        <? endforeach; ?>

    </section>

    <h1 class="secTitle "><?= _("Proximamente...:") ?></h1>
    <section class="mainSections ">

        <?php foreach($eventosFuturos as $evento) : ?>

            <article class="event-card">
                <figure class="event-card-image_title">
                    <img src="/<?php if(strpos($evento->getEPoster(),'serverimg') !== false) echo $evento->getEPoster();  else echo substr_replace( $evento->getEPoster(),'/thumbnail_', strpos($evento->getEPoster(),'/'), 1); ?>" alt="Event Name">
                    <figcaption><?= $evento->getENombre(); ?></figcaption>
                </figure>
                <div class="event-card-info">
                    <div>
                        <div><?= $evento->getPEntrada(); ?> </div>
                        <i>&euro;</i>
                    </div>
                    <div>
                        <time datetime="<?= $evento->getFEvento(); ?>"><?= $evento->getFEvento(); ?></time>
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <div><?= $evento->getEProvincia(); ?></div>
                        <i class="fa fa-map-marker"></i>
                    </div>
                </div>
                <div class="event-card-actions">
                    <a href="/evento/<?= $evento->getId(); ?>/detalles"><<?= _("Ver") ?>><?= _("Ver") ?></a>
                    <?php if(($usuario != null && $evento->getIdCreador() == $usuario->getId() && $evento->getNET() == $evento->getNER()) || \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')): ?>
                        <a href="/evento/<?= $evento->getId(); ?>/edit"><?= _("Editar") ?></a>
                        <a href="/evento/<?= $evento->getId(); ?>/delete"><?= _("Borrar") ?></a>
                    <? endif; ?>
                </div>
            </article>

        <? endforeach; ?>
    </section>

    <?php if(isset($eventosProvinciales) && count($eventosProvinciales) != 0) : ?>
        <h1 class="secTitle "><?= _("Eventos en tu provincia:") ?></h1>
        <section class="mainSections ">

            <?php foreach($eventosProvinciales as $evento) : ?>

                <article class="event-card">
                    <figure class="event-card-image_title">
                        <img src="/<?php if(strpos($evento->getEPoster(),'serverimg') !== false) echo $evento->getEPoster();  else echo substr_replace( $evento->getEPoster(),'/thumbnail_', strpos($evento->getEPoster(),'/'), 1); ?>" alt="Event Name">
                        <figcaption><?= $evento->getENombre(); ?></figcaption>
                    </figure>
                    <div class="event-card-info">
                        <div>
                            <div><?= $evento->getPEntrada(); ?> </div>
                            <i>&euro;</i>
                        </div>
                        <div>
                            <time datetime="<?= $evento->getFEvento(); ?>"><?= $evento->getFEvento(); ?></time>
                            <i class="fa fa-calendar"></i>
                        </div>
                        <div>
                            <div><?= $evento->getEProvincia(); ?></div>
                            <i class="fa fa-map-marker"></i>
                        </div>
                    </div>
                    <div class="event-card-actions">
                        <a href="/evento/<?= $evento->getId(); ?>/detalles"><?= _("Ver") ?></a>
                        <?php if(($usuario != null && $evento->getIdCreador() == $usuario->getId() && $evento->getNET() == $evento->getNER()) || \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')): ?>
                            <a href="/evento/<?= $evento->getId(); ?>/edit"><?= _("Editar") ?></a>
                            <a href="/evento/<?= $evento->getId(); ?>/delete"><?= _("Borrar") ?></a>
                        <? endif; ?>
                    </div>
                </article>

            <? endforeach; ?>

        </section>
    <? endif; ?>
<? endif; ?>

<?php if(isset($usr)) : ?>

    <h1 class="secTitle "> <?= _("Eventos de") ?><?= $usr->getNombre(); ?></h1>
    <p class="navigator"> <a  href="/">Index</a> > <a href="/<?= $usr->getId(); ?> "> <?= $usr->getNombre(); ?></a></p>
    <section class="mainSections ">

        <?php foreach($eventosUsuario as $evento) : ?>

            <article class="event-card">
                <figure class="event-card-image_title">
                    <img src="/<?php if(strpos($evento->getEPoster(),'serverimg') !== false) echo $evento->getEPoster();  else echo substr_replace( $evento->getEPoster(),'/thumbnail_', strpos($evento->getEPoster(),'/'), 1); ?>" alt="Event Name">
                    <figcaption><?= $evento->getENombre(); ?></figcaption>
                </figure>
                <div class="event-card-info">
                    <div>
                        <div><?= $evento->getPEntrada(); ?> </div>
                        <i>&euro;</i>
                    </div>
                    <div>
                        <time datetime="<?= $evento->getFEvento(); ?>"><?= $evento->getFEvento(); ?></time>
                        <i class="fa fa-calendar"></i>
                    </div>
                    <div>
                        <div><?= $evento->getEProvincia(); ?></div>
                        <i class="fa fa-map-marker"></i>
                    </div>
                </div>
                <div class="event-card-actions">
                    <a href="/evento/<?= $evento->getId(); ?>/detalles"><?= _("Ver") ?></a>
                    <?php if(($usuario != null && $evento->getIdCreador() == $usuario->getId() && $evento->getNET() == $evento->getNER()) || \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')): ?>
                        <a href="/evento/<?= $evento->getId(); ?>/edit"><?= _("Editar") ?></a>
                        <a href="/evento/<?= $evento->getId(); ?>/delete"><?= _("Borrar") ?></a>
                    <? endif; ?>
                </div>
            </article>

        <? endforeach; ?>

    </section>
<? endif; ?>