<h1 class="secTitle ">
    <?= isset($user) ? "Editando datos de " . $user->getNombre() : 'Registrarse' ?>
</h1>
<p class="navigator">
    <a href="index.html">Index</a>
    >
    <a href="<?= isset($user) ? '/usuario/' . $user->getId() . '/edit' : '/usuario/registrarse' ?>">
        <?= isset($user) ? "Editando " . $user->getNombre() : 'Registrarse' ?></a>
</p>
<section class="mainSections ">
    <article id="generalForm">
        <form action="<?= isset($user) ? '/usuario/' . $user->getId() . '/update' : '/usuario/create' ?>" method="post" enctype="multipart/form-data">
            <div>
                <label for="nick"><?= _("Nombre de usuario") ?></label>
                <input type="text" id="nick" name="nombre" placeholder="<?= _("Nombre de usuario") ?>" value="<?= isset($user) ? $user->getNombre() : '' ?>">
            </div>
            <div>
                <label for="email"><?= _("Correo") ?></label>
                <input type="text" id="email" name="email" placeholder="<?= _("Correo") ?>" value="<?= isset($user) ? $user->getEmail() : '' ?>">
                <?php if(!isset($user) && !isset($usuario)): ?>
                    <input type="text" id="email" name="email2" placeholder="<?= _("Confirmacion del correo") ?>">
                <? endif; ?>
            </div>
            <div>
                <label for="password"><?= _("password") ?></label>
                <input type="password" id="password" name="passwd" placeholder="<?= _("password") ?>">
                <?php if(!isset($user) && !isset($usuario)): ?>
                    <input type="password" id="password" name="passwd2" placeholder="<?= _("Confirmacion de la password") ?>">
                <? endif; ?>

            </div>
            <div>
                <label for="password"><?= _("Provincia") ?></label>
                <select name="provincia">
                    <option value="" selected disabled> <?= _("Selecciona provincia") ?></option>
                    <?php foreach ($provincias as $provincia) : ?>
                        <option value="<?= $provincia->getProvincia(); ?>" <?php if(isset($user) && $usuario->getProvincia() == $provincia->getProvincia()) echo"selected"; ?>> <?= $provincia->getProvincia(); ?> </option>
                    <? endforeach; ?>
                </select>
            </div>
            <?php if((isset($user) || isset($usuario)) && \liveticket\core\Security::isUserGranted('ROLE_ADMINISTRADOR')) : ?>
                <div>
                    <label for="role"><?= _("Role") ?></label>
                    <select name="role">

                        <?php if (!isset($user)) : ?>
                            <option value="ROLE_COMPRADOR" selected> <?= _("Comprador") ?> </option>
                            <option value="ROLE_GESTOR"> <?= _("Gestor") ?> </option>
                        <? endif; ?>

                        <?php if (isset($user)) : ?>
                            <?php if ($user->getRole() === "ROLE_COMPRADOR") : ?>
                                <option value="ROLE_COMPRADOR" selected> <?= _("Comprador") ?> </option>
                                <option value="ROLE_GESTOR"> <?= _("Gestor") ?> </option>
                            <?php elseif ($user->getRole() === "ROLE_GESTOR") : ?>
                                <option value="ROLE_COMPRADOR"> <?= _("Comprador") ?> </option>
                                <option value="ROLE_GESTOR" selected> <?= _("Gestor") ?> </option>
                            <?php elseif ($user->getRole() === "ROLE_ADMINISTRADOR") : ?>
                             <option value="ROLE_ADMINISTRADOR" selected> <?= _("Administrador") ?> </option>
                            <? endif; ?>
                        <? endif; ?>

                    </select>
                </div>
            <? endif; ?>
            <div>
                <label for="avatar"><?= _("Avatar") ?></label>
                <input type="file" id="avatar" name="avatar">
            </div>
            <div id="captcha">
                <label for="captcha">Captcha</label>
                <img src="/gencaptcha">
                <input type="text" name="captcha" placeholder="captcha">
            </div>
            <div>
                <div class="errores">
                    <?php foreach ($error_messages as $error) :?>
                        <p>
                            <?= $error ?>
                        </p>
                    <? endforeach; ?>
                </div>
                <div class="submit">
                    <input type="submit" value="<?php if(isset($user)) echo "aplicar cambios"; elseif(!isset($user) && isset($usuario)) echo "Registrar cuenta"; else echo"Registrarse"; ?>">
                </div>
            </div>
        </form>
    </article>
</section>