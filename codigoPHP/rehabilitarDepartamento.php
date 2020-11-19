<?php
require_once "../config/confLocation.php";
if(isset($_REQUEST['cancelar'])){
    header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');
}
require_once '../core/201109libreriaValidacion.php';
require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 
try {
        $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones

        $sql = "Select DescDepartamento, FechaBaja, VolumenNegocio from Departamento where CodDepartamento=:CodDepartamento";
        $consulta = $miDB->prepare($sql);//Preparamos la consulta
        $parametros = [":CodDepartamento" => $_REQUEST['codigo']];

        $consulta->execute($parametros);//Pasamos los parámetros a la consulta
        $resultado = $consulta->fetchObject();//Obtenemos el primer registro de la consulta y avanzamos el puntero al siguiente

        $descripcionDepartamento=$resultado->DescDepartamento;//Almacenamos el valor de la descripción del departamento de la conculta en la variable descripcionDepartamento
        $fechaBaja='null';//Establecemos el valor de la fecha a null
        
        $volumenNegocio=$resultado->VolumenNegocio;//Almacenamos el valor del volumen de negocio del departamento de la conculta en la variable volumenNegocio
        
        
    }catch (PDOException $excepcion){
        $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
        $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

        echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
        echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
    } finally {
        unset($miDB);//Cerramos la conexión de la base de datos
    }
    if(isset($_REQUEST['aceptar'])){//Si pulsa el botón de aceptar
        try{
            $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones
            
            $sqlBaja = "UPDATE Departamento set FechaBaja=NULL where CodDepartamento=:CodDepartamento";
            $consultaBaja = $miDB->prepare($sqlBaja);//Preparamos la consulta

            $parametrosBaja = [":CodDepartamento" => $_REQUEST['codigo']];
            $consultaBaja->execute($parametrosBaja);//Pasamos los parámetros a la consulta
            header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');//Redirigimos al usuario a la página inicial
        }catch (PDOException $excepcion){
            $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
            $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

            echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
            echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
            die();
        } finally {
            unset($miDB);//Cerramos la conexión con la base de datos
        }
    }else{
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
        <div class="logo">Mantenimiento de Departamentos - Rehabilitación Departamento</div>
    </header>
    <main class="mainEditar">
        <div class="contenido">
            <form name="formulario" action="<?php echo $_SERVER['PHP_SELF'].'?codigo='.$_REQUEST['codigo'];?>" method="post" class="formularioBaja">
                    <div>
                        <label style="font-weight: bold;" class="CodigoDepartamento" for="CodDepartamento">Código de departamento: </label>
                        <input type="text" id="nombre" style="border: 0" name="CodDepartamento" value="<?php echo $_REQUEST['codigo'] ?>"readonly>
                        <br><br>

                        <label style="font-weight: bold;" class="DescripcionDepartamento" for="DescDepartamento">Descripción de departamento: </label>
                        <input type="text" id="DescDepartamento" style="border: 0" name="DescDepartamento" value="<?php echo $descripcionDepartamento ?>" readonly>
                        <br><br>
                        
                        <label style="font-weight: bold;" class="Fecha" for="Fecha">Fecha: </label>
                        <input type="text" id="Fecha" style="border: 0" name="Fecha" value="null" readonly>
                        <br><br>

                        <label style="font-weight: bold;" class="Volumen" for="VolumenNegocio">Volumen de negocio: </label>
                        <input type="text" id="VolumenNegocio" style="border: 0" name="VolumenNegocio" value="<?php echo $volumenNegocio ?>" readonly>
                        <br><br>
                    </div>
                    <span class="atencion"><img src="../webroot/media/atencion.png" alt="ATENCION" width="20">Rehabilitarás el departamento estableciendo la fecha de baja a NULL</span>
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