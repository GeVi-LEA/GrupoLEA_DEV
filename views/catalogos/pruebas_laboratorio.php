
<span id="valor" hidden>PruebaLaboraorio</span>
<?php if (isset($_SESSION['result']) && $_SESSION['result'] == 'true'): ?>
    <span hidden id="result">true</span>
<?php else : ?>
    <span hidden id="result">false</span>
<?php endif; ?>
<header class="header">
    <div class="row">
    <div class="col-3 text-center"><a href="<?= catalogosUrl ?>?controller=Catalogo&action=showEquipoLaboratorio"><span class="material-icons p-1 i-apps" id="i-apps" title="Equipos">keyboard_backspace</span></a></div>
        <div class="col-6 text-center"><h1 class="titulo">Pruebas Laboratorios</h1></div>
        <div class="col-3 d-flex">
            <div class="mt-2 mr-3"><input class="buscador" type="text" id="buscador" placeholder="Buscar..."><i class="fas fa-search i-search"></i></div>
            <a><span class="material-icons ml-3 mt-1 p-1 i-close" id="i-close" title="Cerrar">cancel</span></a>
        </div>
    </div>
</header>
<nav class="menu">
    <span id="mostrarForm">Agregar Prueba Laboratorio</span>
</nav>
<section id="secForm">
    <form action="<?= catalogosUrl ?>?controller=Catalogo&action=savePruebaLaboratorio" method="post" class="formulario" id="formularioPruebaLaboraorio" >
        <div class="divCancelar">
            <a id="cancel"> <span class="material-icons i-cancel ml-5" title="Cancelar">disabled_by_default</span></a>    
        </div>  
        <input type="text" name="id" class="id" id="id" hidden/>
        <div class="row p-1">
            <div class="col-3 text-right">
                <label for="nombre">Nombre:</label>
            </div>
            <div class="col-9 d-flex justify-content-between">
                <input type="text" name="nombre" class="inputXL" id="nombre"  placeholder="Ej. Prueba densidad"/>           
            </div>
        </div>
        <div class="row p-1 ">
            <div class="col-3 text-right">
                <label for="descripcion">Descripción:</label>
            </div>
            <div class="col-9">
                <input type="text" name="descripcion" class="inputXL" id="descripcion" placeholder="Escribe una descripción"/>
            </div>
        </div>
        <div class="row p-1">
            <div class="col-12 text-center">
                <div >
                    <ul class="error text-left" id="error">
                        <?php if (isset($_SESSION['errores']['nombre']) && $_SESSION['errores']['nombre'] == 'invalid'): ?>
                            <li>El <b>nombre</b> de la prueba ya existe</li>                   
                        <?php endif; ?>
                        endif;
                        Utils::deleteSession('result');
                        Utils::deleteSession('errores');
                        ?>
                    </ul>
                </div>
                <input class="btnAgregar" id="btnAgregar" type="submit" value="Agregar"/>
                <a id="save"><span class="material-icons i-save" title="Actualizar">save</span></a>
            </div>
        </div>
    </form>
</section>
<section class="sec-tabla text-center table-responsive-sm" id="seccionPruebaLaboraorio">
    <table class="table table-condensed" id="tablaPruebaLaboraorio">
        <?php if (!empty($pruebas)): ?>
            <thead>
            <th>Id</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th></th>
            </thead>
            <tbody>
                <?php foreach ($pruebas as $a): ?>
                    <tr class="tr">
                        <td id="idTabla"><?= $a->id; ?></td>
                        <td id="nombreTabla"><?= $a->nombre; ?></td>
                        <td id="descripcionTabla"><?= $a->descripcion; ?></td>
                        <td> 
                            <div>
                                <a ><span id="edit" class="material-icons i-edit" title="Editar">edit</span></a>                    
                                <a id="linkDelete" href="<?= catalogosUrl ?>?controller=Catalogo&action=deletePruebaLaboratrorio&id=<?= $a->id; ?>" ></a><span id="delete" class="material-icons i-delete" title="Eliminar">delete_forever</span>
                            </div>
                        </td>
                    <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <span>No hay Prueba Laboraorio registrados</span>                   
    <?php endif; ?>
</section>