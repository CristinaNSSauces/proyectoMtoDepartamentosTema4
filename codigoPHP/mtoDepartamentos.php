<?php
require_once "../config/confLocation.php";//Incluimos el archivo de configuración para poder acceder a la constante de la url del header Location   
    if(isset($_REQUEST['volver'])){//si pulsa el botón de volver
        header('Location:'.URL.'/proyectoDWES/indexProyectoDWES.php'); //Redirigimos al usuario a la página inicial de DWES
        exit();
    }
    
    if(isset($_REQUEST['mostrarCodigo'])){//Si pulsa el botón de mostrar codigo
        header('Location:'.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mostrarCodigo.php'); //Redirigimos al usuario a la página mostrarCodigo.php
        exit();
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mto Departamentos</title>
    <link href="../webroot/css/style.css" rel="stylesheet"> 
</head>
<body>
    <header>
        <div class="logo">Mantenimiento de Departamentos</div>
        <nav>
            <ul class="enlaces">
                <li><a href="exportarDepartamentos.php"><img src="../webroot/media/exportar.png" alt="EXPORTAR" width="30">EXPORTAR </a></li>
                <li><a href="importarDepartamentos.php"><img src="../webroot/media/importar.png" alt="IMPORTAR" width="30">IMPORTAR </a></li>
                <li><a href="altaDepartamento.php"><img src="../webroot/media/add.png" alt="AÑADIR" width="30">AÑADIR </a></li>
            </ul>
        </nav> 
    </header>
    <main class="mainIndex">
        <div class="contenidoIndex">
            <?php
            /**
                *@author: Cristina Núñez
                *@since: 14/11/2020
            */
                require_once "../core/201109libreriaValidacion.php";//Incluimos la librería de validación para comprobar los campos del formulario
                require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 

                //declaracion de variables universales
                define("OBLIGATORIO", 1);
                define("OPCIONAL", 0);
                $entradaOK = true;

                $error = null;//Inicializamos a null la variable donde almacenaremos los errores del campo
                ?>
            <div class="formBuscar">
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioBuscar">
                            <label for="DescDepartamento" class="descDepartamento">Descripción de departamento: </label>
                            <input type="text" style="background-color: #D2D2D2" id="DescDepartamento" style="background-color: #D2D2D2" name="DescDepartamento" value="<?php echo(isset($_REQUEST['DescDepartamento']) ? $_REQUEST['DescDepartamento'] : null);?>" class="descDepartamento">
                            <?php echo($error!=null ? "<span style='color:red'>".$error."</span>" : null); ?>
                            <input type="submit" value="Buscar" name="buscar" class="enviar">
                </form>
            </div>
            <div class="resultadoConsulta">
            <?php
                
                if (isset($_REQUEST['avanzarPagina'])) {//Si pulsa el botón de avanzar pagina
                    $numPagina = $_REQUEST['avanzarPagina'];//el numero de la pagina es igual al valor de avanzarPagina
                } else if(isset($_REQUEST['retrocederPagina'])){//Si pulsa el botón de retroceder pagina
                    $numPagina = $_REQUEST['retrocederPagina'];//el numero de la pagina es igual al valor de retrocederPagina
                }else if(isset($_REQUEST['paginaInicial'])){//Si pulsa el botón de pagina inicial
                    $numPagina = $_REQUEST['paginaInicial'];//el numero de la pagina es igual al valor de paginaInicial
                }else if(isset($_REQUEST['paginaFinal'])){//Si pulsa el botón de pagina final
                    $numPagina = $_REQUEST['paginaFinal'];//el numero de la pagina es igual al valor de paginaFinal
                }else{//si no pulsa ningun boton
                    $numPagina = 1;//esablecemos el valor del numero de pagina a 1
                }
                
                if(isset($_REQUEST['buscar'])){// Comprobamos si el usuario ha enviado el formulario
                    $error = validacionFormularios::comprobarAlfaNumerico($_REQUEST['DescDepartamento'], 255, 1, OPCIONAL);//Comprobamos que la descripción sea alfanumerico
                    
                    if($error!=null){//Si hay errores
                        $entradaOK = false;
                        $_REQUEST['DescDepartamento'] = "";
                    }
                }else{//Si el usuario no ha enviado el formulario
                    $_REQUEST['DescDepartamento']="";
                }
                if($entradaOK){//Si el usuario ha rellenado correctamente el formulario
     
                    try {
                        $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
                        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

                        $sql = 'SELECT * FROM Departamento WHERE DescDepartamento LIKE "%":DescDepartamento"%" LIMIT '.(($numPagina-1)*MAXDEPARTAMENTOS).','.MAXDEPARTAMENTOS;

                        $consulta = $miDB->prepare($sql);//Preparamos la consulta
                        $parametros = [":DescDepartamento" => $_REQUEST['DescDepartamento']];
                        $consulta->execute($parametros);//Pasamos los parametros y ejecutamos la consulta
                        
                        $sqlPaginacion = 'SELECT count(*) FROM Departamento';
                    
                        $consultaPaginacion = $miDB->prepare($sqlPaginacion); // preparo la consulta

                        $consultaPaginacion->execute(); // ejecuto la consulta con los paremtros del array de parametros 
                        $resultado = $consultaPaginacion->fetch();//Almacenamos el resultado el primer registro de la consulta y avanzamos el puntero al registro siguiente
                        
                        if($resultado[0]%MAXDEPARTAMENTOS==0){//Si el resto de dividir el numero de registros de nuestro departamento entre el total de registros por pagina es cero
                            $numPaginas = ($resultado[0]/MAXDEPARTAMENTOS);//El numero máximo de paginas es la división entre el numero de registros de nuestro departamento entre el total de registros por pagina
                        } else {
                            $numPaginas = floor($resultado[0]/MAXDEPARTAMENTOS)+1;////El numero máximo de paginas es la división entre el numero de registros de nuestro departamento entre el total de registros por pagina mas uno
                        }
                        
                        settype($numPaginas,"integer");//convertimos el numero de paginas totales a integer
                        settype($numPagina,"integer");//convertimos el numero de pagina actual a integer

                        ?>
                        <div class="tabla">
                            <table class="tablaConsultaCampos">
                                <thead>
                                    <tr>
                                        <th class="cDepartamento">Código</th>
                                        <th class="dDepartamento">Descripción</th>
                                        <th class="fDepartamento">FechaBaja</th>
                                        <th class="vDepartamento">VolumenNegocio</th>
                                    </tr>
                                </thead>
                                <tbody>
                <?php
                        if($consulta->rowCount()>0){//Si hay algún resultado

                            $registro = $consulta->fetchObject();//Obtenemos la primera fila del resultado de la consulta y avanzamos el puntero a la siguiente fila
                            while($registro){ //Mientras haya un registro  
                ?>
                                    <tr>
                                        <td class="campo" style="<?php echo($registro->FechaBaja ? 'color: red' : 'color: green'); ?>"><?php echo $registro->CodDepartamento ?></td>
                                        <td class="campo" style="<?php echo($registro->FechaBaja ? 'color: red' : 'color: green'); ?>"><?php echo $registro->DescDepartamento ?></td>
                                        <td class="campo" style="<?php echo($registro->FechaBaja ? 'color: red' : 'color: green'); ?>" class="fecha"><?php echo($registro->FechaBaja ? $registro->FechaBaja : 'null'); ?></td>
                                        <td class="campo" style="<?php echo($registro->FechaBaja ? 'color: red' : 'color: green'); ?>"><?php echo $registro->VolumenNegocio ?></td>

                                        <td class="boton"><button name='editar' value="Editar" style="background-color: transparent; border: 0;" ><a href="<?php echo 'editarDepartamento.php?codigo='.$registro->CodDepartamento ?>"><img src="../webroot/media/editar.png" alt="EDITAR" width="30"></a></button></td>       
                                        <td class="boton"><button name='consultar' value="Consultar" style="background-color: transparent; border: 0;"><a href="<?php echo 'mostrarDepartamento.php?codigo='.$registro->CodDepartamento ?>"><img src="../webroot/media/ver.png" alt="CONSULTAR" width="30"></a></button></td>
                                        <td class="boton"><button name='borrar' value="Borrar" style="background-color: transparent; border: 0;"><a href="<?php echo 'bajaDepartamento.php?codigo='.$registro->CodDepartamento ?>"><img src="../webroot/media/borrar.png" alt="BORRAR" width="30"></a></button></td>
                                        <td class="boton"><button name='bajaLogica' value="BajaLogica" style="background-color: transparent; border: 0;"><a href="<?php echo 'bajaLogicaDepartamento.php?codigo='.$registro->CodDepartamento ?>"><img src="../webroot/media/baja.png" alt="BajaLogica" width="30"></a></button></td>
                                        <td class="boton"><button name='rehabilitar' value="Rehabilitar" style="background-color: transparent; border: 0;"><a href="<?php echo 'rehabilitarDepartamento.php?codigo='.$registro->CodDepartamento ?>"><img src="../webroot/media/rehabilitar.png" alt="Rehabilitar" width="30"></a></button></td>
                                    </tr> 
                        <?php 
                                $registro = $consulta->fetchObject();//Obtenemos la siguiente fila del resultado de la consulta y avanzamos el puntero a la siguiente fila
                            }
                        
                        ?>
                                </tbody>
                            </table>
                            <div class="paginacion">
                                <form name="formularioPaginacion" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                                    <table>
                                        <br>
                                        <tr>
                                            <td class="tBoton"><button class="pagina" <?php echo ($numPagina==1 ? "hidden" : null);?> type="submit" name="paginaInicial" value="1"><img src="../webroot/media/pagInicial.png" alt="Rehabilitar" width="30"></button></td>
                                            <td class="tBoton"><button class="pagina" <?php echo ($numPagina==1 ? "hidden" : null);?> type="submit" name="retrocederPagina" value="<?php echo $numPagina-1;//si pulsa el boton de retocederPagina restamos uno a la pagina actual?>"><img src="../webroot/media/pagAnterior.png" alt="Rehabilitar" width="30"></button></td>
                                            <td><?php echo ($numPagina.' de '.$numPaginas); ?></td>
                                            <td class="tBoton"><button class="pagina" <?php echo ($numPagina>=$numPaginas ? "hidden" : null);?> type="submit" name="avanzarPagina" value="<?php echo $numPagina+1;//si pulsa el boton de retocederPagina sumamos uno a la pagina actual?>"><img src="../webroot/media/pagSiguiente.png" alt="Rehabilitar" width="30"></button></td>
                                            <td class="tBoton"><button class="pagina" <?php echo ($numPagina>=$numPaginas ? "hidden" : null);?> type="submit" name="paginaFinal" value="<?php echo $numPaginas;//numero total de páginas | página final?>"><img src="../webroot/media/pagFinal.png" alt="Rehabilitar" width="30"></button></td>
                                        </tr>
                                    </table>   
                                </form>
                            </div>
                        
                        
                <?php 
                    }else{
                        ?>
                                <tr>
                                    <th rowspan="4" style="color:red;">No se han encontrado registros</th>
                                </tr>
                            </tbody>
                        </table>
                <?php
                    }
                ?>
                        <form  name="formularioconsulta" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                            <table class="botones">
                                <tr>
                                    <td>
                                        <button type="submit" name='volver' value="Volver" class="volver">VOLVER</button>
                                    </td>
                                    <td>
                                        <button name='mostrarCodigo' value="mostrarCodigo" class="volver">Mostrar Código</button>
                                    </td>
                                </tr>
                            </table> 
                        </form>
                    </div>
                </div>
                            
                <?php       
                    }catch (PDOException $excepcion) { //Código que se ejecutará si se produce alguna excepción
                        $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
                        $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

                        echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
                        echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
                    } finally {
                        unset($miDB);
                    }
                }
            ?>
        </div>
    </main>
    
    <footer>
        <address>Cristina Núñez Sebastián</address>
    </footer>
</body>
</html>