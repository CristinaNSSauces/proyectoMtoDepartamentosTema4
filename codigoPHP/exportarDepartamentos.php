<?php
/**
    *@author: Cristina Núñez
    *@since: 18/11/2020
*/

require_once "../config/confDBPDO.php";  
    try{
        $miDB = new PDO(DNS, USER, PASSWORD); //Establezco la conexión a la base de datos instanciado un objeto PDO.
        $miDB ->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Cuando se produce un error lanza una excepción utilizando PDOException.

        $sql = "SELECT * from Departamento";
        $consulta = $miDB ->prepare($sql);//Preparamos la consulta
        $consulta->execute();//Ejecutamos la consulta

        $archivoXML = new DOMDocument("1.0", "utf-8"); //Creamos un objeto DOMDocument con dos parámetros, la versión y la codificación del documento
        $archivoXML->formatOutput = true; //Formateamos la salida

        $nodoDepartamentos = $archivoXML->appendChild($archivoXML->createElement("Departamentos"));//Creamos el nodo departamentos

        $registro =  $consulta->fetchObject();//Obtenemos el primer registro de la consulta y avanzamos el puntero al siguiente
        while($registro){//Mientras el resultado del registro no sea null
            $nodoDepartamento  = $nodoDepartamentos->appendChild($archivoXML->createElement("Departamento"));//Creamos un hijo dentro del nodo departamentos llamado departamento
            $nodoDepartamento->appendChild($archivoXML->createElement("CodDepartamento", $registro->CodDepartamento)); //Creamos un hijo dentro del nodo departamento llamado CodDepartamento y le asignamos el valor correspondiente
            $nodoDepartamento->appendChild($archivoXML->createElement("DescDepartamento", $registro->DescDepartamento)); //Creamos un hijo dentro del nodo departamento llamado DescDepartamento y le asignamos el valor correspondiente
            $nodoDepartamento->appendChild($archivoXML->createElement("FechaBaja", $registro->FechaBaja)); //Creamos un hijo dentro del nodo departamento llamado FechaBaja y le asignamos el valor correspondiente 
            $nodoDepartamento->appendChild($archivoXML->createElement("VolumenNegocio", $registro->VolumenNegocio)); //Creamos un hijo dentro del nodo departamento llamado volumenNegocio y le asignamos el valor correspondiente

            $registro =  $consulta->fetchObject();//Obtenemos el siguiente registro de la consulta y avanzamos el puntero al siguiente
        }
        $archivoXML->save("../tmp/tablaDepartamento.xml");//Guardamos el archivo XML en la carpeta tmp del servidor

        header('Content-Type: text/xml');//Tipo del archivo
        header('Content-Disposition: attachment; filename="tablaDepartamento.xml"');//Nombre del archivo de la descarga
        readfile("../tmp/tablaDepartamento.xml");//Ubicación del archivo

    }catch (PDOException $excepcion) { //Código que se ejecutará si se produce alguna excepción
        $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
        $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion

        echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
        echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
    } finally {
        unset($miDB);//Cerramos la conexión con la base de datos
    }
?>