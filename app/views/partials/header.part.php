<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="/css/estiloglobal.css">
    <link rel="stylesheet" type="text/css" href="/css/fontawesome-all.min.css">
    <title>LiveTicket</title>
</head>

<body>
<div id="container">
    <form action="/evento/<?php if(isset($usr)) echo"{$usr->getId()}/"; ?>listado" id="formmain" method="post" class="eventformdetect">
        <header class="containerPadding" id="container-header">
            <h1 id="logo">
                <a href="/"> LiveTicket </a>
            </h1>
            <div id="mainInput">
                <input type="text" <?php if(isset($patronbusqueda)) echo "value='$patronbusqueda'"; ?> name="patronbusqueda" placeholder="Buscar evento por nombre, autor, desc... ">
                <i id="showfilters" class="fa fa-filter fa-2x"></i>
            </div>
            <nav>
                <?php if($usuario === null) : ?>
                    <i class="auth fa fa-user fa-2x"></i>
                <? endif; ?>

                <i class="fa fa-bars fa-2x" id="openNavLateral" aria-hidden="true"></i>
                <!--i class="fa fa-question fa-2x"></i-->
            </nav>
        </header>
        <header id="container-filter-header" class="hideMainFilters containerPadding">
            <div class="filter">
                <div>
                    <label for="fecha"> <?= _("Buscar entre fechas: ") ?></label>
                </div>
                <div>
                    <select name="fecharangobusqueda">
                        <option <?php if(!isset($fecharangobusqueda) || isset($fecharangobusqueda) && empty($fecharangobusqueda)) echo 'selected';?> value=""><?= _("todas las fechas") ?> </option>
                        <option <?php if(isset($fecharangobusqueda) && $fecharangobusqueda == 'ma') echo 'selected';?> value="ma"><?= _("manyana") ?></option>
                        <option <?php if(isset($fecharangobusqueda) && $fecharangobusqueda == 'se') echo 'selected';?> value="se"><?= _("esta semana") ?></option>
                        <option <?php if(isset($fecharangobusqueda) && $fecharangobusqueda == 'we') echo 'selected';?> value="we"><?= _("este fin de semana") ?></option>
                        <option <?php if(isset($fecharangobusqueda) && $fecharangobusqueda == 'me') echo 'selected';?> value="me"><?= _("Este mes") ?></option>
                    </select>
                    <input type="date" <?php if(isset($fechabusqueda)) echo "value='$fechabusqueda'"; ?> name="fechabusqueda">
                    <input type="date" <?php if(isset($fechabusqueda2)) echo "value='$fechabusqueda2'"; ?> name="fechabusqueda2">
                    <i class="fa fa-calendar"></i>
                </div>
            </div>
            <div class="filter">
                <div>
                    <label> <?= _("Provincia: ") ?></label>
                </div>
                <div>
                    <select name="provinciabusqueda">
                        <option <?php if(!isset($provincias) || isset($provincias) && empty($provincias)) echo 'selected';?>  value=""><?= _("todas") ?></option>
                        <?php foreach ($provincias as $provincia) : ?>
                           <option value="<?= $provincia->getProvincia(); ?>" <?php if(isset($provinciabusqueda) && $provinciabusqueda == $provincia->getProvincia()) echo "selected"; ?>><?= $provincia->getProvincia(); ?></option>
                       <? endforeach;?>
                    </select>
                    <i class="fa fa-globe"></i>
                </div>
            </div>
            <div class="filter">
                <div>
                    <label for="categoria"> <?= _("Categoria: ") ?></label>
                </div>
                <div>
                    <select name="categoriabusqueda" id="categoria">
                        <option <?php if(!isset($categorias) || isset($categorias) && empty($categorias)) echo 'selected';?> value=""><?= _("todas") ?></option>
                        <?php foreach ($categorias as $categoria) : ?>
                            <option <?= $categoria->getCategoria(); ?> <?php if(isset($categoriabusqueda) && $categoriabusqueda == $categoria->getCategoria()) echo"selected"; ?>><?= $categoria->getCategoria(); ?></option>
                        <? endforeach;?>
                    </select>
                    <i class="fa fa-music" aria-hidden="true"></i>
                </div>
            </div>
            <div class="filter ">
                <div>
                    <label for="disponibles"> <?= _("Mostrar solo disponibles:") ?></label>
                </div>
                <div>
                    <input type="checkbox" id="cbox1" name="disponibles" <?php if(isset($disponibles) && $disponibles == true) echo "checked"; ?>>
                    <i class="fa fa-check" aria-hidden="true"></i>
                </div>
            </div>
            <div class="filter">
                <div>
                    <label for="ordenado"><?= _("ordenar por:") ?></label>
                </div>
                <div>
                    <select name="ordenadobusqueda" id="detectchanges">
                        <option value="" disabled <?php if(!isset($ordenadobusqueda) || $ordenadobusqueda == "") echo "selected"; ?>></option>
                        <option value="EFecha" <?php if(isset($ordenadobusqueda) && $ordenadobusqueda == "EFecha") echo "selected"; ?>><?= _("Fecha") ?></option>
                        <option value="ECategoria" <?php if(isset($ordenadobusqueda) && $ordenadobusqueda == "ECategoria") echo "selected"; ?>><?= _("Categoria") ?></option>
                        <option value="EProvincia" <?php if(isset($ordenadobusqueda) && $ordenadobusqueda == "Provincia") echo "selected"; ?>><?= _("Provincia") ?></option>
                    </select>
                </div>
            </div>
            <div class="filter submitFilters">
                <input type="submit" value="Buscar">
            </div>
        </header>
    </form>

    <?php include __DIR__ . '/menu.part.php' ?>

    <?php  if($usuario === null) : ?>

        <div id="overlay"></div>
        <div id="modal" class="modalHidden">
            <h1>
                <?= _("Autenticarse") ?>
            </h1>
            <form action="/check-login" method="POST">
                <div>
                    <input type="text" class="field" name="nombre" />
                    <label class="floating-label"><?= _("Nombre de usuario") ?></label>
                    <span class="underline"></span>
                </div>
                <div>
                    <input type="password" class="field" name="contraseña" />
                    <label class="floating-label"><?= _("password") ?></label>
                    <span class="underline"></span>
                </div>
                <div>
                    <div>
                        <a href="/usuario/registrarse"><?= _("No tienes cuenta? !Registrate") ?></a>
                    </div>
                    <div>
                        <input type="submit" value="identificarse">
                    </div>
                </div>
            </form>
        </div>

    <? endif; ?>

    <main class="containerPadding">
