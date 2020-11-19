<?php
require_once "../config/confLocation.php";
    if(isset($_REQUEST['volver'])){
        header('Location: '.URL.'/proyectoMtoDepartamentosTema4/codigoPHP/mtoDepartamentos.php');
        exit();
    }
    echo "<h3>Mostrando el código de confLocation.php</h3>";
    highlight_file('../config/confLocation.php');
    
    echo "<h3>Mostrando el código de mtoDepartamntos.php</h3>";
    highlight_file('mtoDepartamentos.php');
    
    echo "<h3>Mostrando el código de altaDepartamento.php</h3>";
    highlight_file('altaDepartamento.php');
    
    echo "<h3>Mostrando el código de bajaDepartamento.php</h3>";
    highlight_file('bajaDepartamento.php');
    
    echo "<h3>Mostrando el código de editarDepartamento.php</h3>";
    highlight_file('editarDepartamento.php');
    
    echo "<h3>Mostrando el código de mostrarDepartamento.php</h3>";
    highlight_file('mostrarDepartamento.php');
    
    echo "<h3>Mostrando el código de bajaLogicaDepartamento.php</h3>";
    highlight_file('bajaLogicaDepartamento.php');
    
    echo "<h3>Mostrando el código de rehabilitarDepartamento.php</h3>";
    highlight_file('rehabilitarDepartamento.php');
    
    echo "<h3>Mostrando el código de importarDepartamentos.php</h3>";
    highlight_file('importarDepartamentos.php');
    
    echo "<h3>Mostrando el código de exportarDepartamentos.php</h3>";
    highlight_file('exportarDepartamentos.php');
    
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
    <main>
        <div class="contenido">
            <form  name="formularioconsulta" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
                <button type="submit" name='volver' value="Volver" class="volver">VOLVER</button>
            </form>         
        </div>
    </main>
</body>
</html>

