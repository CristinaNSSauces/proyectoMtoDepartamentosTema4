<?php

/**
 * Class validacionFormularios
 *
 * Clase que contiene funciones para validar los campos de los formularios
 *
 * PHP version 7.0
 *
 * @category Validacion
 * @package  Validacion
 * @source ClaseValidacion.php
 * @since Versión 1.1 Se han formateado los mensajes de error y modificado validarDni()
 * @since Version 1.2 Se han acabado de formatear los mensajes de error, se han modificado validarURL() y se han añadido validarCp(), validarPassword(), validarRadioB() y validarCheckBox()
 * @copyright 21-10-2018
 * @author Versión 1.3 Adrián Cando Oviedo
 * @version Versión 1.3 Se han modificado la devolución de varias funciones: comprobarNoVacío, comprobarMintamanio, comprobarMaxTamanio, comprobarEntero, comprobarFloat, antes estas 3 devolvían un valor boolean, ahora
 * devuelven una cadena con el mensaje de error. Estas 3 anteriores funciones se emplean en otras 3 funciones que he cambiado, algo más compuestas las cuales son: comprobarAlfabético, 
 * @since version 1.4 Mejorado los métodos comprobarEntero() y comprobarFlaoat()
 * comprobarAlfanumerico y validarEmail. También he eliminado una función inservible "comprobarCódigo". Este cambio se basa en simplificar la cantidad de código ya que antes los * errores
 * @since version 1.5 mejorada la ortografía de los mensajes de error
 * se escribian cada vez que querías mostrarlos ahora ya los devuelve cada función a la que se ha llamado sin tener que escribir nada.
 * 
 */
class validacionFormularios {  //ELIMINA EL METODO VALIDATEDATE Y LO INCLUYE EN VALIDAR FECHA, ELIMINACION DE VALIDAR CHECKBOX Y RADIOB, MEJORA GENERAL DE RESPUESTA, INCLUSION DE PARAMETROS PREDEFINIDOS Y MEJORAS SUSTANCIALES EN ALGUNOS METODOS

    /**
     * @function comprobarAlfabetico();
     * @author Adrián Cando Oviedo
     * @version 1.0 He eliminado todos los if innecesrios que había simplificandolo a llamar a las funciones internas de errores que devuelven un error si le hay
     * concatenando esos errores en una cadena. Y comprobando que está vacío siempre que sea obligatorio. He añadido algunos comentarios explicando los nuevos cambios.
     * @since 2018-10-23
     * @param $cadena Cadena que se va a comprobar.
     * @param $maxTamanio Tamaño máximo de la cádena.
     * @param $minTamanio Tamaño mínimo de la cadena.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     *         $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     *      */
    public static function comprobarAlfabetico($cadena, $maxTamanio = 1000, $minTamanio = 1, $obligatorio = 0) {  //AÑADIDOS VALORES POR DEFECTO Y MEJORADA LA RESPUESTA
        // Patrón para campos de solo texto
        $patron_texto = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙñÑ\s]+$/";
        $cadena = htmlspecialchars(strip_tags(trim((string) $cadena)));
        $mensajeError = null;

        //Si es olbigatorio se comprueba si está vacío, si no es obligatorio, no es necesario
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($cadena);
        }
        
        //Comprobación de que la cadena introducida coincide con la sintaxis permitida del patrón
        if (!preg_match($patron_texto, $cadena) && !empty($cadena)) {
            $mensajeError = " Solo se admiten letras.";
        }
        $mensajeError .= self::comprobarMaxTamanio($cadena, $maxTamanio);
        $mensajeError .= self::comprobarMinTamanio($cadena, $minTamanio);
        return $mensajeError;
    }

// Función para comprobar un campo AlfaNumerico
// Return nada si es correcto, si hay errores devuelve un mensaje de error
// Si es un 1 es obligatorio, si es un 0 no lo es
    /**
     * @function comprobarAlfaNumerico();        
     * @author Adrián Cando Oviedo
     * @version 1.0 He eliminado todos los if innecesrios que había simplificandolo a llamar a las funciones internas de errores que devuelven un error si le hay
     * concatenando esos errores en una cadena. Y comprobando que está vacío siempre que sea obligatorio. He añadido algunos comentarios explicando los nuevos cambios.
     * @since 2018-10-23
     * @param $cadena Cadena que se va a comprobar.
     * @param $maxTamanio Tamaño máximo de la cádena.
     * @param $minTamanio Tamaño mínimo de la cadena.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     *              $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     */
    public static function comprobarAlfaNumerico($cadena, $maxTamanio = 1000, $minTamanio = 1, $obligatorio = 0) {  //AÑADIDOS VALORES POR DEFECTO Y MEJORADA LA RESPUESTA
        $cadena = htmlspecialchars(strip_tags(trim((string) $cadena)));
        $mensajeError = null;
        //Si es obligatorio se hace la comprobación de que no está vacío
        if ($obligatorio == 1 && $cadena != '0') {
            $mensajeError = self::comprobarNoVacio($cadena);
        }
        $mensajeError .= self::comprobarMaxTamanio($cadena, $maxTamanio);
        $mensajeError .= self::comprobarMinTamanio($cadena, $minTamanio);
        return $mensajeError;
       
    }

// Función para comprobar si es un campo entero
// Return null es correcto, si no muestra el mensaje de error
// Si es un 1 es obligatorio, si es un 0 no lo es
    /**
     * @author Christian Muñiz de la Huerga
     * @function comprobarEntero();
     * @param $integer Número entero a comprobar
     * @param $max Valor máximo del entero
     * @param $min Valor mínimo del entero
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null en el caso en el que esté correcto, si no devuelve una cadena con el mensaje de error.
     */
    public static function comprobarEntero($integer, $max = PHP_INT_MAX, $min = -PHP_INT_MAX, $obligatorio = 0){  //AÑADIDOS VALORES POR DEFECTO Y AHORA DETECTA EL 0
        $mensajeError = null;
        
        if ($obligatorio == 1 && $integer != '0') {
            $mensajeError = self::comprobarNoVacio($integer);
        }
        
        if(($obligatorio == 0 && $integer!=null) || ($obligatorio == 1 && empty($mensajeError))){//COMPROBAMOS QUE SI ES OPCIONAL, NO ESTÉ VACÍO Y SI ES OBLIGATORIO QUE NO HAYA GUARDADO UN MENSAJE DE ERROR ANTERIOR (QUE EL CAMPO NO ESTÉ VACÍO)  
            $integer = str_replace('.', ',', $integer);//SI SE HA INTRODUCIDO UN NÚMERO CON '.'(FLOAT), SUSTITUIMOS EL PUNTO POR UNA COMA PARA QUE SEA UN STRING
            if (!is_numeric($integer)){ //SI NO ES UN NÚMERO O STRING NUMÉRICO
                $mensajeError = "El campo no es un entero. ";
            }else{
                if($integer>$max){
                    $mensajeError = $mensajeError."El número no puede ser mayor que ".$max.".";
                }
                if($integer<$min){
                    $mensajeError = $mensajeError."El número no puede ser menor que ".$min.".";
                }
            }
        }
        
        return $mensajeError;
    }

// Función para comprobar si es un campo float
// Return null es correcto, si no muestra el mensaje de error
// Si es un 1 es obligatorio, si es un 0 no lo es
    /**
     * @author Christian Muñiz de la Huerga
     * @function comprobarFloat();
     * @param $float Número entero a comprobar
     * @param $max Valor máximo del entero
     * @param $min Valor mínimo del entero
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null en el caso en el que esté correcto, si no devuelve una cadena con el mensaje de error.
     */
    public static function comprobarFloat($float, $max = PHP_FLOAT_MAX, $min = -PHP_FLOAT_MAX, $obligatorio = 0){  //AÑADIDOS VALORES POR DEFECTO Y AHORA DETECTA 0
        $mensajeError = null;
        if ($obligatorio == 1 && $float != '0') {
            $mensajeError = self::comprobarNoVacio($float);
        }
        
        if(($obligatorio == 0 && $float!=null) || ($obligatorio == 1 && empty($mensajeError))){//COMPROBAMOS QUE SI ES OPCIONAL, NO ESTÉ VACÍO Y SI ES OBLIGATORIO QUE NO HAYA GUARDADO UN MENSAJE DE ERROR ANTERIOR (QUE EL CAMPO NO ESTÉ VACÍO)   
            if (!is_numeric($float)) {//SI NO ES UN NÚMERO O STRING NUMÉRICO
                $mensajeError = "El campo no es un decimal. (Debe llevar punto(.) entre la parte entera y la parte decimal)";   
            }else{
                if($float>$max){
                    $mensajeError = $mensajeError."El número no puede ser mayor que ".$max.".";
                }
                if($float<$min){
                    $mensajeError = $mensajeError."El número no puede ser menor que ".$min.".";
                }
            }
        }
        return $mensajeError;
    }

// Función para comprobar si es un correo electronico
// Return nada si es correcto, si hay errores devuelve un mensaje de error
// Si es un 1 es obligatorio, si es un 0 no lo es
// 
    /**
     * @function validarEmail();
     * @author Adrián Cando Oviedo
     * @version 1.3 He modificado el tratamiento de los mensajes de error, y las comprobaciones, adaptadas a la nueva forma de los mensajes. He eliminado los if innecesarios
     * He añadido nuevos comentarios explicando el nuevo funcionamiento.
     * @since 2018-10-23
     * @param $email Cadena a comprobar.
     * @param $maxTamanio Tamaño máximo de la cadena.
     * @param $minTamanio Tamaño mínimo de la cadena.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     *              $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     */
    public static function validarEmail($email, $obligatorio = 0) { //ELIMINADoS MAX Y MIN, IMPLEMENTACION DE PARAMETRO POR DEFECTO Y MEJORADA  LA RESPUESTA
        $mensajeError = null;

        //Compruebo si está vacío cuadno es obligatorio
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($email);
        }

        //Comprobación de que la sintaxis del correo introducido es correcta
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) { //HE SIMPLIFICADO ESTO
            $mensajeError = " Formato de correo incorrecto(Ejemplo: tunombre@hotmail.com).";
        }
        return $mensajeError;
    }

// Función para comprobar si es una url, local o no
// Devuelve null si es correcto, sino muestra el mensaje de error
// Si el parámetro $obligatorio es un 1 es obligatorio, si es un 0 es opcional
    /**
     * @function validarURL();
     * @author Christian Muñiz de la Huerga
     * @param $url Cadena a comprobar.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null si es correcto o un mensaje de error en caso de que lo haya.
     */
    public static function validarURL($url, $obligatorio = 0) { //MEJORADA LA RESPUESTA Y ASIGNADO VALOR POR DEFECTO
        $mensajeError = null;
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($url); 
        }
        if (!filter_var($url, FILTER_VALIDATE_URL) && !empty($url)){
            $mensajeError = "Formato incorrecto de URL.";
        }        
        return $mensajeError;
    }

    /**
     * @function validarFecha();
     * @param $fecha Cadena a comprobar.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null si es correcto o un mensaje de error en caso de que lo haya.
     */
    public static function validarFecha($fecha, $fechaMaxima = '01/01/2200', $fechaMinima = "01/01/1900", $obligatorio = 0) { //REDISEÑO TOTAL Y AÑADIDOS PARAMETROS INICIALES
        $mensajeError = null;
        $fechaMaxima = strtotime($fechaMaxima); //PASAR A TIMESTAMP PARA PODER OPERAR
        $fechaMinima = strtotime($fechaMinima);
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($fecha);
        }
        $fechaFormateada = strtotime($fecha);  //CREAR FECHA PARA TRABAJAR CON LAS FUNCIONES DE PHP

        if (is_bool($fechaFormateada) && !empty($fecha)) {
            $mensajeError = " Formato incorrecto de fecha (Año-Mes-dia) (2000-01-01).";
        } else {
            if(!empty($fecha) && ($fechaFormateada < $fechaMinima) || ($fechaFormateada > $fechaMaxima)){
                $mensajeError = " Por favor introduzca una fecha entre " . date('d/m/Y', $fechaMinima) . " y " . date('d/m/Y', $fechaMaxima) . ".";
            }
        }
        return $mensajeError;
    }

    /**
     * @function validarDni();
     *
     * @param $dni cadena a comprobar.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null si es correcto o un mensaje de error en caso de que lo haya.
     */

    /**
     * Si no es obligatorio, da por válido un campo vacío o un DNI, si lo es, será necesario introducir un DNI bien formateado
     * @function validarDni();
     * @author Mario Casquero Jañez
     * @param $dni cadena a comprobar.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null si es correcto o un mensaje de error en caso de que lo haya.
     */
    public static function validarDni($dni, $obligatorio = 0) { //AÑADIDO VALOR POR DEFECTO Y MEJORADA LA SALIDA
        $mensajeError = null;
        $letra = substr($dni, -1);
        $numeros = substr($dni, 0, -1);
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($dni);
        }
        if(!is_numeric($letra) && is_numeric($numeros)){
            if ((substr("TRWAGMYFPDXBNJZSQVHLCKE", $numeros % 23, 1) != $letra || strlen($letra) != 1 || strlen($numeros) != 8) && !empty($dni)) {
                $mensajeError = " El DNI no es válido.";
            }
        }else{
            if(!empty($dni)){
                $mensajeError = " El DNI no es válido.";
            }
        }
        return $mensajeError;
    }

    // Valida el código postal, si es opcional da por válido que sea correcto o este vacío, si es obligatorio solo da por válido que esté correcto
    /**
     * @function validarCp();
     * @author Mario Casquero Jañez
     * @param $cp cadena a comprobar.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null si es correcto o un mensaje de error en caso de que lo haya.
     */
    public static function validarCp($cp, $obligatorio = 0){  //AÑADIDO PARAMETRO INDEFINIDO Y SALIDA MEJORADA
        $mensajeError = null;
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($cp);
        }

        if (!preg_match('/^[0-9]{5}$/i', $cp) && !empty($cp)){
            $mensajeError=" El código postal no es válido.";
        }
        return $mensajeError;
    }

    // Valida el password, comprueba longitud y si al menos contiene una mayúscula y un número, si es opcional da por válido que sea correcto o este vacío, si es obligatorio solo da por válido que esté correcto
    /**
     * @function validarPassword();
     * @author Mario Casquero Jañez
     * @param $passwd cadena a comprobar.
     * @param $minimo valor que indica la longitud mínima de la contraseña
     * @param $obligatorio valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null si es correcto o un mensaje de error en caso de que lo haya.
     */
    public static function validarPassword($passwd, $minimo = 2, $obligatorio = 0) {  //CAMBIADO ORDEN DE LOS PARAMETROS, AÑADIDOS PARAMETROS PREDEFINIDOS Y MEJORADA LA RESPUESTA
        $mensajeError = null;
        if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($passwd); 
        }
        if (strlen($passwd) < $minimo && !empty($passwd)){
            $mensajeError = " La contraseña debe ser del al menos " . $minimo . " caracteres.";
        }
        if ((!preg_match("`[A-Z]`", $passwd) || !preg_match("`[0-9]`", $passwd)) && !empty($passwd)) {
            $mensajeError .= " La contraseña debe contener una mayúscula y un número.";
        }
        
        return $mensajeError;
    }

// Función para validar si no esta vacio
// Return null si está vacío el mensajeError, cadena con error si es que lo hay  
// Función para validar si no esta vacio
// Return false esta vacio, true no esta vacio
    /**
     * @function comprobarNoVacio();
     * @author Adrián Cando Oviedo
     * @version 1.3 Pequeño cambio a la hora de la devolución. Antes devolvía un valor boolean, ahora una cadena con el error o sin él
     * @since 2018-10-23
     * @param $cadena Cadena a comprobar que no está vacía.
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     *              $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     */
    public static function comprobarNoVacio($cadena) {
        $mensajeError = null;
        $cadena = htmlspecialchars(strip_tags(trim($cadena)));

        if (empty($cadena)) {
            $mensajeError = " Campo vacío.";
        }
        return $mensajeError;
    }

// Función para tamaño maximo
// Si tamaño es 0 significa que no tiene limite
// Return false no es correcto, true es correcta
    /**
     * @function comprobarMaxTamanio();
     * @author Adrián Cando Oviedo
     * @version 1.3 Pequeño cambio a la hora de la devolución. Antes devolvía un valor boolean, ahora una cadena con el error o sin él
     * @since 2018-10-23
     * @param $cadena Cadena para comprobar
     * @param $tamanio Longitud de la cadena
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     *              $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     */
    public static function comprobarMaxTamanio($cadena, $tamanio) {
        $mensajeError = null;
        if (strlen($cadena) > $tamanio) {
            $mensajeError = " El tamaño máximo es de " . $tamanio . " caracteres.";
        }
        return $mensajeError;
    }

// Función para tamaño minimo
// Si el tamaño es 0 significa que no tiene limite
// Return false no es correcto, true es correcta
    /**
     * @function comprobarMinTamanio();
     * @author Adrián Cando Oviedo
     * @version 1.3 Pequeño cambio a la hora de la devolución. Antes devolvía un valor boolean, ahora una cadena con el error o sin él
     * @since 2018-10-23
     * @param $cadena Cadena para comprobar
     * @param $tamanio Longitud de la cadena
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     *              $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     */
    public static function comprobarMinTamanio($cadena, $tamanio) {
        $mensajeError = null;
        if (strlen($cadena) < $tamanio && strlen($cadena) > 0) { //AÑADIDA SEGUNDA COMPROBACIÓN. Para que cuando el campo esté vacío no muestre este mensaje, sólo cuando haya mínimo 1 caracter para advertir del tamaño mínimo
            $mensajeError = " El tamaño mínimo es de " . $tamanio . " caracteres.";
        }
        return $mensajeError;
    }

// Función para validar la fecha
// Recibe una fecha y un formato el por defecto es año-mes-dia
// Devuelve True si es una fecha valida y un false si no la es
    /**
     * @function validateFecha();
     *
     * @param $date Fecha a comprobar
     * @param string $format formato de la cadena
     * @return bool Devuelve null si es correcto, y si no devuelve un error
     */
  /*  public static function validateDate($date, $format = 'Y-m-d') {        ELIMINAR
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }
 */
    /**
     * @author Christian Muñiz de la Huerga
     * @function validarElementoEnLista();
     * @param $elementoElegido Elemento introducido que se va a comprobar.
     * @param $aOpciones Array con los posibles valores posibles con el que se va a comparar el elemento.
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es.
     * @return null|string Devuelve null en el caso en el que esté correcto, sino devuelve una cadena con el mensaje de error.
     */
    public static function validarElementoEnLista($elementoElegido, $aOpciones) {   //NO TIENE SENTIDO HACER UNA LISTA NO OBLIGATORIA
        $mensajeError = null; //Inicializa el mensaje de error a null.

        if (!in_array($elementoElegido, $aOpciones)) { 
            $mensajeError = " El elemento no se encuentra entre los posibles valores.";
        }
        return $mensajeError; //Devuelve el mensaje de error.
    }

    /**
     * @function validaTelefono(); 
     * @author Tania Mateos
     * @version 1.3 Modificada la comprobación de si está vacio. Modificada la devolución de la función, ahora devuelve nada o un mensaje de error.
     * @param $tel Telefono que se va a comprobar. 
     * @param $obligatorio Valor booleano indicado mediante 1, si es obligatorio o 0 si no lo es. 
     * @return null -> Si la variable errores es null o en su defecto contiene tan sólo espacios en blanco
     * $mensajeError -> Que contiene una cadena con los errores que han surgido concatenados.
     * @since 2020-10-19
     * @author Luis Puente Fernández
     * @version 1.4 Corregido la variable en la que se almacena el mensaje de error en caso de estar vacio la cual era distinta a la que se devolvía, tambien se ha cambiado el nombre de la variable mensaje a mensajeError, que es mas descriptivo
     */
    
    public static function validaTelefono($tel, $obligatorio = 0){ //AÑADIDO PARAMETRO POR DEFECTO, MEJORADA LA FUNCIONALIDAD Y LA SALIDA
        $mensajeError=null;
        $patron="/^[6|7|9][0-9]{8}$/";
         if ($obligatorio == 1) {
            $mensajeError = self::comprobarNoVacio($tel);
        }
        if (!preg_match($patron, $tel) && !empty($tel)) {
            $mensajeError .= " El telefono debe comenzar por 6,7 o 9 y a continuación 8 dígitos del 0 al 9.";
        }
        return $mensajeError; 
    }
}

?>