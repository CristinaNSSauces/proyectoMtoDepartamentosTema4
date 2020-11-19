<?php
        /**
            *@author: Cristina Núñez
            *@since: 27/10/2020
            * (ProyectoTema4) Conexión a la base de datos con la cuenta usuario y tratamiento de errores
        */ 
            
        require_once "../config/confDBPDO.php";//Incluimos el archivo confDBPDO.php para poder acceder al valor de las constantes de los distintos valores de la conexión 
        
            try {
                $miDB = new PDO(DNS,USER,PASSWORD);//Instanciamos un objeto PDO y establecemos la conexión
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);//Configuramos las excepciones
                
                $sql = <<<EOD
                        INSERT INTO Departamento(CodDepartamento,DescDepartamento,FechaBaja,VolumenNegocio) VALUES
                        ('INF', 'Departamento de informatica',null,1),
                        ('VEN', 'Departamento de ventas',null,2),
                        ('CON', 'Departamento de contabilidad',null,3),
                        ('COC', 'Departamento de cocina',null,4),
                        ('MEC', 'Departamento de mecanica',null,5),
                        ('MAT', 'Departamento de matematicas',null,6);
EOD;
                
                $miDB->exec($sql);
                
                echo "<h3> <span style='color: green;'>"."Valores insertados</span></h3>";//Si no se ha producido ningún error nos mostrará "Conexión establecida con éxito"
            }
            catch (PDOException $excepcion) {//Código que se ejecutará si se produce alguna excepción
                $errorExcepcion = $excepcion->getCode();//Almacenamos el código del error de la excepción en la variable $errorExcepcion
                $mensajeExcepcion = $excepcion->getMessage();//Almacenamos el mensaje de la excepción en la variable $mensajeExcepcion
                
                echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";//Mostramos el mensaje de la excepción
                echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;//Mostramos el código de la excepción
            } finally {
                unset($miDB);
            }
?>