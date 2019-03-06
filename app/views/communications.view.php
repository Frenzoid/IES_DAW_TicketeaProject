
<h1 class="secTitle ">
    Mensajes:
</h1>
<p class="navigator">
    <a href="">Index</a>>
    <a href="/mensajes">Chat</a>
</p>

<section class="mainSections ">
    <article id="comunication-panel">
        <aside id="com-pan-lateral">
            <div>
                <h2><?= _("Todos los usuarios") ?></h2>
                <ul>
                    <?php foreach ($allUsers as $user ) : ?>
                        <?php if($user->getId() != $usuario->getId()) : ?>
                            <li>
                                <a href="/mensajes/<?= $user->getId(); ?>"><?= $user->getNombre(); ?></a>
                            </li>
                        <? endif; ?>
                    <? endforeach; ?>
                </ul>
            </div>
            <div>
                <h2><?= _("Chats recientes") ?></h2>
                <ul>
                    <?php foreach ($openedChats as $user ) : ?>
                        <li>
                            <a href="/mensajes/<?= $user->getId(); ?>"><?= $user->getNombre(); ?></a>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </aside>
        <div id="com-pan-chat">
            <?php if($openedChat == null || ($openedChat != null && $openedChat->getId() == $usuario->getId()))  : ?>
                <div>
                    <h2>
                        <?= _("Selecciona un usuario para mandarle un mensaje.") ?>
                    </h2>
                </div>
            <? endif; ?>


            <?php if($openedChat != null && $openedChat->getId() != $usuario->getId()) : ?>
                <div>
                    <a href="/usuario/<?= $openedChat->getId(); ?>/perfil">
                        <h2><?= _("Chat con") ?>
                            <strong> <?= $openedChat->getNombre(); ?> </strong>
                        </h2>
                    </a>
                    <ul id="com-pan-chat-msg">
                        <?php foreach ($messages as $message): ?>
                            <li <?= $message->mine ? "class='mine'" : ""; ?>><small><?= $message->getFecha(); ?></small><br><?= $message->getMensaje(); ?></li>
                        <? endforeach; ?>
                    </ul>
                </div>
                <form method="POST" action="/mensajes/<?= $openedChat->getId(); ?>/enviar">
                    <input type="text" name="mensaje" placeholder="mensaje">
                    <input type="submit" value="enviar">
                </form>
            <? endif; ?>

        </div>
    </article>
</section>
