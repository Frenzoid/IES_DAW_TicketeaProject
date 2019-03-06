<h1 class="secTitle ">
    <?= _("Detalles del evento: ") ?> <?= $event->getENombre(); ?>
</h1>
<p class="navigator">
    <a href="/">Index</a>>
    <a href="/evento/listados"><?= _("Eventos Listados")?></a>>
    <a href="/evento/<?= $event->getId(); ?>/detalles"><?= _("Detalles del evento")?></a>
</p>
<section class="mainSections ">
    <article id="event-details">
        <div id="event-main">
            <h2> <?= $event->getENombre(); ?></h2>
            <figure>
                <img src="/<?= $event->getEPoster(); ?>">
            </figure>
            <h3><?= _("Categoria:")?> <?= $event->getECategoria(); ?></h3>
            <p>
                <?= $event->getEDesc(); ?>
            </p>
            <?php if($event->getEExterno() != null && !empty($event->getEExterno())) :?>
                <h3><a href="http://<?= $event->getEExterno(); ?>"><?= _("Mas informacion: ")?></a> <?= $event->getEExterno(); ?></h3>
            <? endif; ?>
        </div>

        <aside id="event-tramit-info">
            <div>
                <h3> <?= $event->getEProvincia(); ?> </h3>
                <p> <?= $event->getEDir(); ?></p>
                <div>
                    <?= _("Fecha del evento:")?>
                    <strong> <?= $event->getFEvento(); ?> </strong>
                </div>
            </div>
            <div>
                <div>
                    <?= _("Entradas disponibles desde:") ?>
                    <strong> <?= $event->getFVInicio(); ?> </strong>
                </div>
                <div>
                    <?= _("Entradas disponibles hasta:") ?>
                    <strong> <?= $event->getFVFinal(); ?> </strong>
                </div>
                <div>
                    <?= _("Entradas disponibles:") ?>
                    <strong> <?= $event->getNER(); ?> </strong>
                </div>
                <div>
                    <?= _("Precio x Entrada:") ?>
                    <strong> <?= $event->getPEntrada(); ?>
                        <i>&euro;</i>
                    </strong>
                </div>
                <?php if(strtotime('today') >= strtotime($event->getFVInicio()) && strtotime('today') <= strtotime($event->getFVFinal()) && $event->getNER() != "0") : ?>
                    <form method="POST" action="/tramites/<?= $event->getId(); ?>/tramitar">
                        <label for="cantidad"><?= _("Cant. Entradas a comprar") ?></label>
                        <div>
                            <input type="number" name="numentradas" value="1">
                            <input <?php if(isset($usuario)) echo "type='submit'"; else echo "class='auth' type='button'"; ?> value="comprar">
                        </div>
                        <div class="errores">
                            <?php foreach ($error_messages as $error) :?>
                                <p>
                                    <?= $error ?>
                                </p>
                            <? endforeach; ?>
                        </div>
                    </form>
                <? endif; ?>
            </div>
            <div>
                <h3> <?= _("Anfitrion:") ?> </h3>
                <div>
                    <figure>
                        <a <?php if(isset($usuario)) echo "href='/usuario/" . $eventcreator->getId() . "/perfil'"; else echo "class='auth'"; ?>> <img src="/<?= $eventcreator->getAvatar(); ?>"></a>
                    </figure>
                    <div>
                       <h4><?= $eventcreator->getNombre(); ?></h4>
                        <div>
                            <a <?php if(isset($usuario)) echo "href='/mensajes/" . $eventcreator->getId() . "'"; else echo "class='auth'"; ?>><?= _("Mandar mensaje") ?></a>
                            <a <?php if(isset($usuario)) echo "href='/" . $eventcreator->getId() . "'"; else echo "class='auth'"; ?>><?= _("Ver Eventos") ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </article>
    <iframe
        width="100%"
        height="500"
        frameborder="0" style="border:0"
        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBGYN63Nou7olyRe0ePvLYl_3xoMrvJlJ8&q=<?= $event->getEDir(); ?>, <?= $event->getEProvincia(); ?>" allowfullscreen>
    </iframe>
</section>
