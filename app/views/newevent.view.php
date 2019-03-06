<h1 class="secTitle ">
    <?= isset($event) ? 'Editando evento ' . $event->getENombre() : 'Crear Evento'?>
</h1>
<p class="navigator">
    <a href="">Index</a>>
    <a href="<?= isset($event) ? '/evento/' . $event->getId() . '/edit' : '/evento/newevent' ?>"><?= isset($event) ? _('editando evento') . $event->getENombre() : _('Crear Evento')?></a>
</p>
<section class="mainSections ">
    <article id="generalForm" class="create-evet-form">
        <form action="<?= isset($event) ? '/evento/' . $event->getId() . '/update' : '/evento/create' ?>" method="post" enctype="multipart/form-data">
            <div class="form-pack">
                <div>
                    <label for="desc"><?= _("Descripcion") ?></label>
                    <input value="<?= isset($event) ? $event->getEDesc() : '' ?>" type="input" id="desc" placeholder="<?= _("Descripcion del evento") ?>" name="EDesc">
                </div>
                <div>
                    <label for="ENombre"><?= _("Nombre del evento") ?></label>
                    <input value="<?= isset($event) ? $event->getENombre() : '' ?>" type="text" id="nick" name="ENombre" placeholder="nombre del evento">
                </div>
            </div>

            <div class="form-pack">
                <div>
                    <label for="nick"><?= _("Provincia") ?></label>
                    <select name="EProvincia">
                        <option disabled selected value=""><?= _("selecciona provincia") ?></option>
                        <?php foreach ($provincias as $provincia) : ?>
                            <option value="<?= $provincia->getProvincia(); ?>" <?php if(isset($event) && $event->getEProvincia() == $provincia->getProvincia()) echo "selected"; ?> ><?= $provincia->getProvincia(); ?></option>
                        <? endforeach;?>
                    </select>
                </div>
                <div>
                    <label for="dir"><?= _("Direccion") ?></label>
                    <input value="<?= isset($event) ? $event->getEDir() : '' ?>" type="text" id="dir" name="EDir" placeholder="direccion">
                </div>
            </div>

            <div class="form-pack">
                <div>
                    <label for="date"><?= _("Fecha del Evento") ?></label>
                    <input value="<?= isset($event) ? $event->getFEvento() : '' ?>" type="date" id="date" name="FEvento">
                </div>
                <div>
                    <label for="categoria"> <?= _("Categoria") ?> </label>
                    <select name="ECategoria">
                        <option disabled selected value=""><?= _("seleccionar categoria") ?></option>
                        <?php foreach ($categorias as $categoria) : ?>
                            <option value="<?= $categoria->getCategoria(); ?>" <?php if(isset($event) && $event->getECategoria() == $categoria->getCategoria()) echo "selected"; ?>> <?= $categoria->getCategoria(); ?></option>
                        <? endforeach;?>
                    </select>
                </div>
            </div>

            <div class="form-pack">
                <div>
                    <label for="precio"><?= _("Precio") ?></label>
                    <input value="<?= isset($event) ? $event->getPEntrada() : '' ?>" type="number" step="0.01" min=0 name="PEntrada" placeholder="precio" >
                </div>
                <div>
                    <label for="EPoster"><?= _("Poster") ?></label>
                    <input  type="file" name="EPoster" id="poster">
                </div>
            </div>

            <div class="form-pack">
                <div>
                    <label for="FVFinal"><?= _("Ventas disponible hasta") ?></label>
                    <input value="<?= isset($event) ? $event->getFVFinal() : '' ?>" type="date" name="FVFinal">
                </div>
                <div>
                    <label for="FVInicio"><?= _("Ventas disponible desde") ?></label>
                    <input value="<?= isset($event) ? $event->getFVInicio() : '' ?>" type="date" name="FVInicio">
                </div>
            </div>

            <div class="form-pack">
                <div>
                    <label for="NET"><?= _("Cantidad de entradas disponibles") ?></label>
                    <input value="<?= isset($event) ? $event->getNET() : '' ?>" type="number" name="NET">
                </div>
                <div>
                    <label for="enlace"><?= _("Enlace externo (opcional)") ?></label>
                    <input value="<?= isset($event) ? $event->getEExterno() : '' ?>" type="text" name="EExterno">
                </div>
            </div>

            <div class="form-pack">
                <div class="errores">
                    <?php foreach ($error_messages as $error) :?>
                        <p>
                            <?= $error ?>
                        </p>
                    <? endforeach; ?>
                </div>
                <div>
                    <input type="submit" value="<?= isset($event) ? 'Actualizar' : 'Crear' ?>">
                    <a href="/evento/listado"><?= _("Cancelar") ?></a>
                </div>
            </div>
        </form>
    </article>
</section>