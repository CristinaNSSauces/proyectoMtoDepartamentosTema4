<?php
/**
 *   @author: Cristina Núñez
 *   @since: 04/11/2020
 */

require_once '../config/confLocation.php';//Incluimos el archivo de configuración para poder acceder a la constante de la url del header Location   
if (isset($_REQUEST['cancelar'])) {//Si pulsa el botón de cancelar
    header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');//Redirigimos al usuario a la página inicial
    exit;
}

require_once '../config/confDBPDO.php';//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 

$entradaOK = true;

$errorArchivo = null;//Inicializamos la variable donde almacenaremos los errores del campo a null

if (isset($_REQUEST['Aceptar'])) {//Si pulsa el boton de aceptar

    if ($_FILES['archivo']['type'] != 'text/xml') {//Si la extension del archivo no es xml
        $errorArchivo = "El fomato de archivo debe ser .xml";
    }
    
    if($errorArchivo!=null){//Si hay algún error 
        $_REQUEST['archivo']=null;
        $entradaOK=false;
    }
}else{
    $entradaOK=false;
}

if ($entradaOK) {//Si los campos son correctos
    try { // Bloque de código que puede tener excepciones en el objeto PDO
        $miDB = new PDO(DNS, USER, PASSWORD); // creo un objeto PDO con la conexion a la base de datos
        $file_name = $_FILES['archivo']['tmp_name'];
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Establezco el atributo para la apariciopn de errores y le pongo el modo para que cuando haya un error se lance una excepcion
        move_uploaded_file($file_name, '../tmp/copiaDeSeguridad.xml');//Movemos el archivo al tmp con el nombre que deseemos
        
        //Eliminamos los registros de la tabla
        $sqlVaciarTabla = "TRUNCATE TABLE Departamento";
        $consultaVaciarTabla = $miDB->prepare($sqlVaciarTabla);//Preparamos la consulta
        $consultaVaciarTabla -> execute();//Ejecutamos la consulta
        
        
        $sql = <<<EOD
                    Insert into Departamento values
                    (:CodDepartamento, :DescDepartamento, :FechaBaja, :VolumenNegocio);
EOD;
        $consulta = $miDB->prepare($sql);//Preparamos la consulta

        $archivoXML = new DOMDocument("1.0", "utf-8"); //Creamos un objeto DOMDocument con dos parámetros, la versión y la codificación del documento
        $archivoXML->load('../tmp/copiaDeSeguridad.xml'); //Cargamos el documento XML

        $numeroDepartamentos = $archivoXML->getElementsByTagName('Departamento')->count();//Guardamos el número de departamentos que hay en el archivoXML
        for ($numeroDepartamento = 0; $numeroDepartamento<$numeroDepartamentos; $numeroDepartamento++){//Recorremos los departamentos

            $CodDepartamento=$archivoXML->getElementsByTagName("CodDepartamento")->item($numeroDepartamento)->nodeValue;//Guardamos el valor del elemento del cógido de departamento
            $DescDepartamento=$archivoXML->getElementsByTagName("DescDepartamento")->item($numeroDepartamento)->nodeValue;//Guardamos el valor del elemento de la descripción del departamento
            $FechaBaja=$archivoXML->getElementsByTagName("FechaBaja")->item($numeroDepartamento)->nodeValue;//Guardamos el valor del elemento de la fecha de baja
            if(empty($FechaBaja)){//Si el elemento de la feha de baja está vacío
                $FechaBaja = null;//Le asignamos el valor de null para que no de error a la hora de insertar en la base de datos
            }
            $VolumenNegocio=$archivoXML->getElementsByTagName("VolumenNegocio")->item($numeroDepartamento)->nodeValue;//Guardamos el valor del elemento del volumen de negocio

            //Asignamos al array parametros los diferentes valores de los campos guardados
            $parametros = [":CodDepartamento" => $CodDepartamento,
                           ":DescDepartamento" => $DescDepartamento,
                           ":FechaBaja" => $FechaBaja,
                           ":VolumenNegocio" => $VolumenNegocio];
            $consulta -> execute($parametros);//Ejecutamos la consulta con los parámetros
        }
        header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');//Redirigimos al usuario a la página inicial
    } catch (PDOException $miExceptionPDO) { // Codigo que se ejecuta si hay alguna excepcion
        echo "<p style='color:red;'>Código de error: " . $miExceptionPDO->getCode() . "</p>"; // Muestra el codigo del error
        echo "<p style='color:red;'>Error: " . $miExceptionPDO->getMessage() . "</p>"; // Muestra el mensaje de error
        die(); // Finalizo el script
    } finally { // codigo que se ejecuta haya o no errores
        unset($miDB); // destruyo la variable 
    }
}
?> 
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Mto Departamentos</title>
        <link href="../webroot/css/style.css" rel="stylesheet">
    </head>
    <body>
        <header>
            <div class="logo">Mantenimiento de Departamentos - Importar Departamento</div>
        </header>
        <main class="mainEditar">
            <div class="contenido">
                <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="formularioEditar" enctype="multipart/form-data">
                    <div>
                        <label for="archivo">Archivo XML </label>
                        <input id="archivo" name="archivo" type="file">
                        <?php
                            echo($errorArchivo != null) ? "<span style='color:#FF0000'>" . $errorArchivo . "</span>" : null; // si el campo es erroneo se muestra un mensaje de error
                         ?>
                        <br><br>
                    </div>
                    <div>
                        <input type="submit" style="background-color: #a3f27b;" value="Aceptar" name="Aceptar" class="aceptar">
                        <input type="submit" style="background-color: #f27b7b;" value="Cancelar" name="cancelar" class="cancelar">
                    </div>
                </form>
            </div>
        </main>
    </body>
</html>