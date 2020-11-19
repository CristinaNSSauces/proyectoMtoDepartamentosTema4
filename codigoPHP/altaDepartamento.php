<?php
require_once "../config/confLocation.php"; //Incluimos el archivo de configuración para poder acceder a la constante de la url del header Location
    
if(isset($_REQUEST['cancelar'])){//Si pulsa el botón de cancelar
    header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');//Redirigimos al usuario a la página inicial
}
    /**
        *@author: Cristina Núñez
        *@since: 17/11/2020
    */
    require_once '../core/201109libreriaValidacion.php';//Incluimos la librería de validación para comprobar los campos del formulario
    require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 

    //declaracion de variables universales
    define("OBLIGATORIO", 1);
    define("OPCIONAL", 0);
    $entradaOK = true;


    //Declaramos el array de errores y lo inicializamos a null
    $aErrores = ['CodDepartamento' => null,
                 'DescDepartamento' => null,
                 'VolumenNegocio' => null];

    //Declaramos el array del formulario y lo inicializamos a null
    $aFormulario = ['CodDepartamento' => null,
                    'DescDepartamento' => null,
                    'VolumenNegocio' => null];

    if(isset($_REQUEST['aceptar'])){ //Comprobamos que el usuario haya enviado el formulario
        $aErrores['CodDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['CodDepartamento'], 3, 3, OBLIGATORIO);//Comprobamos que el código de departamento sea alfanumérico
        $aErrores['DescDepartamento'] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['DescDepartamento'], 255, 1, OBLIGATORIO);//Comprobamos que la descripción del departamento sea alfanumérico
        $aErrores['VolumenNegocio'] = validacionFormularios::comprobarFloat($_REQUEST['VolumenNegocio'], PHP_FLOAT_MAX, -PHP_FLOAT_MAX, OBLIGATORIO);//Comprobamos que el volumen de negocio sea float
        try{//Comprobamos que el codigo de departamento no se encuentre en la base de datos
            $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

            $sqlCod = "SELECT CodDepartamento from Departamento where CodDepartamento='{$_REQUEST['CodDepartamento']}'";
            $consultaCod = $miDB->prepare($sqlCod);//Preparamos la consulta
            $consultaCod->execute();//Ejecutamos la consulta

            if($consultaCod->rowCount()>0){//Si el código de departamento ya existe
                $aErrores['CodDepartamento'] = "Ese código de departamento ya existe";//Añadimos un mensaje de error al array de errores
            }
        }catch(PDOException $excepcion){
            $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
            $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

            echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
            echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
        } finally {
           unset($miDB); //cerramos la conexion con la base de datos
        } 
        
        // Recorremos el array de errores
        foreach ($aErrores as $campo => $error){
            if ($error != null) { // Comprobamos que el campo no esté vacio
                $entradaOK = false; // En caso de que haya algún error le asignamos a entradaOK el valor false para que vuelva a rellenar el formulario      
                $_REQUEST[$campo] = "";//Limpiamos el campo
            }
        }
    }else{
        $entradaOK = false; // Si el usuario no ha enviado el formulario asignamos a entradaOK el valor false para que rellene el formulario
    }
    if($entradaOK){ // Si el usuario ha rellenado el formulario correctamente rellenamos el array aFormulario con las respuestas introducidas por el usuario
        $aFormulario['CodDepartamento'] = strtoupper($_REQUEST['CodDepartamento']);//Añadimos el código del departamento formateado a mayúsculas al array aFormulario
        $aFormulario['DescDepartamento'] = $_REQUEST['DescDepartamento'];//Añadimos la descripción del departamento al array aFormulario
        $aFormulario['VolumenNegocio'] = $_REQUEST['VolumenNegocio'];//Añadimos el volumen de negocio al array aFormulario

        try {
            $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

            $sql = <<<EOD
               INSERT INTO Departamento (CodDepartamento, DescDepartamento, VolumenNegocio) VALUES 
                    (:CodDepartamento, :DescDepartamento, :VolumenNegocio); 
EOD;
            $consulta = $miDB->prepare($sql);//Preparamos la consulta
            $parametros = [ ":CodDepartamento" => $aFormulario['CodDepartamento'],
                            ":DescDepartamento" => $aFormulario['DescDepartamento'],
                            ":VolumenNegocio" => $aFormulario['VolumenNegocio'] ];

            $consulta->execute($parametros);//Pasamos los parámetros a la consulta
            header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');//Redirigimos al usuario a la página inicial

        }catch (PDOException $excepcion){
            $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
            $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

            echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
            echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
        } finally {
            unset($miDB);//Cerramos la conexión con la base de datos
        }
    }else{//Si el usuario no ha rellenado el formulario correctamente volvera a rellenarlo
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
        <div class="logo">Mantenimiento de Departamentos - Alta Departamento</div>
    </header>
    <main class="mainEditar">
        <div class="contenido">
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioAlta">
                <div>
                    <label style="font-weight: bold;" class="CodigoDepartamento" for="CodDepartamento">Código de departamento: </label>
                    <input type="text" style="background-color: #D2D2D2" id="CodDepartamento" name="CodDepartamento" value="<?php echo(isset($_REQUEST['CodDepartamento']) ? $_REQUEST['CodDepartamento'] : null); ?>">
                    <?php echo($aErrores['CodDepartamento']!=null ? "<span style='color:red'>".$aErrores['CodDepartamento']."</span>" : null); ?>
                    <br><br>

                    <label style="font-weight: bold;" class="DescripcionDepartamento" for="DescDepartamento">Descripción de departamento: </label>
                    <input type="text" style="background-color: #D2D2D2" id="DescDepartamento" name="DescDepartamento" value="<?php echo(isset($_REQUEST['DescDepartamento']) ? $_REQUEST['DescDepartamento'] : null);?>">
                    <?php echo($aErrores['DescDepartamento']!=null ? "<span style='color:red'>".$aErrores['DescDepartamento']."</span>" : null); ?>
                    <br><br>

                    <label style="font-weight: bold;" class="Volumen" for="VolumenNegocio">Volumen de negocio: </label>
                    <input type="text" style="background-color: #D2D2D2" id="VolumenNegocio" name="VolumenNegocio" value="<?php echo(isset($_REQUEST['VolumenNegocio']) ? $_REQUEST['VolumenNegocio'] : null);?>">
                    <?php echo($aErrores['VolumenNegocio']!=null ? "<span style='color:red'>".$aErrores['VolumenNegocio']."</span>" : null); ?>
                    <br><br>
                </div>
                <span class="atencion"><img src="../webroot/media/atencion.png" alt="ATENCION" width="20">El código del departamento se introducirá convertido a mayúsculas</span>
                <div>
                    <input type="submit" style="background-color: #a3f27b;" value="Aceptar" name="aceptar" class="aceptar">
                    <input type="submit" style="background-color: #f27b7b;" value="Cancelar" name="cancelar" class="cancelar">
                </div>
            </form>
        </div>
    </main>
</body>
</html>
<?php
    }
?>
