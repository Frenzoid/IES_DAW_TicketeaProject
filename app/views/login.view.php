<h1 class="secTitle ">
    Iniciar Sesión:
</h1>
<p class="navigator">
    <a href="/index">Index</a> > <a href="/login">Login</a>
</p>
<section class="mainSections ">
    <article id="generalForm">
        <form action="/check-login" method="POST">
            <div>
                <label for="nick"><?= _("Nombre de usuario") ?></label>
                <input type="text" id="nick" placeholder="username" name="nombre">
            </div>
            <div>
                <label for="password"><?= _("Password") ?></label>
                <input type="password" id="password" placeholder="password" name="contraseña">
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
                    <input type="submit" value="identificarse">
                </div>
            </div>
        </form>
    </article>
</section>