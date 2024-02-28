
<span id="valor" hidden>ChoferTransportista</span>
<?php if (isset($_SESSION['result']) && $_SESSION['result'] == 'true'): ?>
    <span hidden id="result">true</span>
<?php else : ?>
    <span hidden id="result">false</span>
<?php endif; ?>
<header class="header">
    <div class="row">
        <div class="col-1 text-center"><a href="<?= catalogosUrl ?>?controller=Catalogo&action=showTransportistasClientes"><span class="material-icons p-1 i-apps" id="i-apps" title="Transportistas">keyboard_backspace</span></a></div>
        <div class="col-8 text-center"><h1 class="titulo">Choferes</h1></div>
        <div class="col-3 d-flex">
            <div class="mt-2 mr-3"><input class="buscador" type="text" id="buscador" placeholder="Buscar..."><i class="fas fa-search i-search"></i></div>
            <a><span class="material-icons ml-3 mt-1 p-1 i-close" id="i-close" title="Cerrar">cancel</span></a>
        </div>
    </div>
</header>
<nav class="menu">
    <span id="mostrarForm">Agregar chofer</span>
</nav>
<section id="secForm">
    <form action="<?= catalogosUrl ?>?controller=Catalogo&action=saveChoferTransportista" method="post" class=" w-100 px-4 formulario" id="formularioChoferTransportista" >
        <div class="divCancelar">
            <a id="cancel"> <span class= "material-icons i-cancel ml-5" title="Cancelar">disabled_by_default</span></a>
        </div>
        <div class="row d-flex justify-content-between p-1">
            <input type="text" name="id" class="id" id="id" hidden />
            <div>
                <label for="nombre">Nombres:</label>
                <input type="text" name="nombre" class="inputBig" id="nombre" maxlength="200" placeholder="Ej. Nombre"/> 
            </div>
            <div>
                <label for="apelllido">Apellidos:</label>
                <input type="text" name="apellido" class="inputBig" id="apellido" maxlength="250" placeholder="Ej.,Apellido"/> 
            </div>
            <div>
                <label for="transportista">Transportista:</label>     
                <select name="transportista" class="inputBig" id="ciudad"> 
                    <option value="" selected disabled>--Selecciona--</option>
                    <?php
                    if (!empty($transportistas)):
                        foreach ($transportistas as $tr):
                            ?>
                            <option value="<?=$tr['idTransportista']?>"><?= $tr['nombreTransportista']?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
            </div>
        </div>
        <div class="row d-flex justify-content-between p-1">
             <div>
                <label for="licencia">Licencia:</label>
                <input type="text" name="licencia" class="inputBig" id="licencia"  placeholder="Ej.55668899"/> 
            </div>
            <div>
                <label for="direccion">INE:</label>
                <input type="text" name="direccion" class="inputBig " id="direccion" maxlength="250" placeholder="Escribe la dirección del cliente"/> 
            </div>
            <div>
                <label for="ciudad">Comentarios:</label>     
                <select name="ciudad" class="inputBig" id="ciudad"> 
                    <option value="" selected disabled>--Selecciona--</option>
                    <?php
                    if (!empty($ciudades)):
                        foreach ($ciudades as $c):
                            ?>
                            <option value="<?= $c->id ?>"><?= $c->ciudad_completa ?></option>
                            <?php
                        endforeach;
                    endif;
                    ?>
                </select>
                <a href="<?= catalogosUrl ?>?controller=Catalogo&action=showCiudades"><span title="Agregar ciudad" class="material-icons i-add p-1">add</span></a>
            </div>
              <div>
                <label for="codigoPostal">C.P.</label>
                <input type="text" name="codigoPostal" class="inputSmall" id="codigoPostal" maxlength="5" placeholder="Ej.55665"/> 
            </div>
        </div>
        <div class="row d-flex justify-content-between p-1">
            <div>
                <label for="correo">Correo:</label>
                <input type="text" name="correo" class="inputLarge" id="correo" maxlength="100" placeholder="nombre.apellido@dominio.com"/> 
            </div>
             <div>
                <label for="rfc">RFC:</label>
                <input type="text" name="rfc" class="inputMedium text-uppercase" id="rfc" maxlength="25" placeholder="Ingresa RFC"/> 
            </div>
          
            <div>
                <label for="fechaAlta">Fecha Alta:</label>
                <input type='text' name="fechaAlta" id="fechaAlta" class="inputSelectMin" readOnly />
            </div>

        </div>
        <div class="row p-1">
            <div class="col-12 text-center">
                <div >
                    <ul class="error text-left" id="error">
                        <?php if (isset($_SESSION['errores']['nombre']) && $_SESSION['errores']['nombre'] == 'invalid'): ?>
                            <li>El <b>nombre</b> de Cliente ya existe</li>                   
                        <?php endif; ?>
                        <?php if (isset($_SESSION['errores']['clave']) && $_SESSION['errores']['clave'] == 'invalid'): ?>
                            <li>La <b>clave</b> de Cliente ya existe</li>                
                            <?php
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
<section class="sec-tabla sec-big text-center table-responsive-sm" id="seccionChoferTransportista">
    <?php if (!empty($choferes)): ?>
        <table class=" table-condensed tabla-big" id="tablaChoferTransportista">
            <thead class="titulos-datos" id="titulos">
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Transportista</th>
            <th>Licencia</th>
            <th>Vigencia</th>
            <th>INE</th>
            <th>Comentarios</th>
            <th></th>
            </thead>
            <tbody>
                <?php foreach ($choferes as $c): ?>             
                    <tr class="tr">
                        <td id="idTabla" hidden><?= $c->chof_id; ?></td>
                        <td id="nombreTabla"><?= $c->chof_nombres; ?></td>
                        <td id="apellidoTabla"><?= $c->chof_apellidos; ?></td>
                        <td id="transportistaTabla"><?= $c->cat_trans_nombre; ?></td>
                        <td id="licenciaTabla"><?= $c->chof_licencia; ?></td>
                        <td id="vigenciaTabla"><?= $c->chof_vigencia; ?></td>
                        <td id="ineTabla"><?= $c->chof_ine; ?></td>
                        <td id="comentariosTabla"><?= $c->chof_comentarios; ?></td>
                        <td> 
                            <div>
                                <a ><span id="edit" class="material-icons i-edit" title="Editar">edit</span></a>                    
                                <a id="linkDelete" href="<?= catalogosUrl ?>?controller=Catalogo&action=deleteChoferTransportista&id=<?= $c->id; ?>" ></a><span id="delete" class="material-icons i-delete" title="Eliminar">delete_forever</span>
                            </div>
                        </td>
                    </tr> 
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <span>No hay choferes registrados</span>                   
    <?php endif; ?>
</section>