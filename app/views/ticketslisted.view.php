<h1 class="secTitle">
    Mis tickets:
</h1>
<p class="navigator">
    <a href="">Index</a>>
    <a href="/tramites">Tickets</a>
</p>
<section class="mainSections force-flex-col">

    <?php foreach($facturas as $factura) :?>

        <article class="list-item">

            <figure class="list-event-image_caption">
                <img src="/<?php if(strpos($factura->EPoster,'serverimg') !== false) echo $factura->EPoster;  else echo substr_replace( $factura->EPoster,'/thumbnail_', strpos($factura->EPoster,'/'), 1); ?>" alt="Event Name">
                <figcaption><a href="/evento/<?= $factura->getEventoid(); ?>/detalles"><?= $factura->ENombre;?></a></figcaption>
            </figure>

            <div class="list-item-info">
                <div>
                    <i class="fa  fa-asterisk"></i>
                    <div><?= $factura->ENombre;?></div>
                    <p><?= _("Evento") ?>: </p>
                </div>
                <div>
                    <i class="fa fa-calendar"></i>
                    <div><?= $factura->getFechacompra();?></div>
                    <p><?= _("Fecha compra") ?>: </p>
                </div>
                <div>
                    <i>&euro;</i>
                    <div><?= floatval($factura->EPrecio) * floatval($factura->getCantidad());?> </div>
                    <p><?= _("Precio total") ?>: </p>
                </div>
            </div>
            <div class="list-item-info">
                <div>
                    <i class="fa fa-map-marker"></i>
                    <div><?= $factura->getIP();?></div>
                    <p> <?= _("Comprado desde") ?>: </p>
                </div>
                <div>
                    <i class="fa fa-calendar"></i>
                    <time datetime="<?= $factura->EFecha;?>"><?= $factura->EFecha;?></time>
                    <p><?= _("Fech. Evento") ?>: </p>
                </div>
                <div>
                    <i class="fa fa-list-ol"></i>
                    <div><?= $factura->getCantidad();?></div>
                    <p><?= _("Cantidad de usos x persona") ?>: </p>
                </div>
            </div>
            <div class="list-item-actions">
                <a class="mcodigo"><?= _("Mostrar codigo") ?></a>
                <a class="codigo"> <?= $factura->getBarcode();?></a>
            </div>
        </article>

    <? endforeach; ?>

</section>