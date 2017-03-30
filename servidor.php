<?php
/**
 * Created by IntelliJ IDEA.
 * User: sebastian
 * Date: 20/02/17
 * Time: 18:40
 */
include_once("lib/nusoap.php");
$ns = "http://localhost/sigc11appws/";
$server = new soap_server();  
$server->configureWSDL('SIG11APPWS', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

//********************Se registra el menú*****************//
$server->register('loginUser', array('usuario' => 'xsd:string', 'clave' => 'xsd:string'), array('return' => 'xsd:int'), $ns);

$server->register('newUser', array('nControl' => 'xsd:int', 'usuario' => 'xsd:string', 'clave' => 'xsd:string'),
    array('return' => 'xsd:int'), $ns);

$server->register('getEmpresa', array('empresa' => 'string'), array('return' => 'xsd:string'), $ns);

$server->register('getConDetail', array('solicitud' => 'xsd:string'), array('return' => 'xsd:string'), $ns);


//********************Funcion para crear el login del usuario*************************//
function loginUser($usuario, $clave)
{
    $mysqli = new mysqli('localhost', 'root', '', 'users_sigc');

    if ($mysqli->connect_errno) {
        die("Fallo la conexión" . $mysqli->mysqli_connect_errno() . ")" . $mysqli->mysqli_connect_errno());

    }

    if ($resultado = $mysqli->query("SELECT * FROM usuarios WHERE usuario = '" . $usuario . "' AND password='" . $clave . "'")) {

        if ($resultado->num_rows != 0) {
            return new soapval('return', 'xsd:int', 1);
        } else {
            return new soapval('return', 'xsd:int', 0);
        }

        $resultado->close();
    }
}


//********************Funcion para agregar Usuarios*****************//
function newUser($nCont, $usuario, $clave)
{
    $mysqli = new mysqli('localhost', 'root', '', 'users_sigc');

    if ($mysqli->connect_errno) {
        die("Fallo la conexión" . $mysqli->mysqli_connect_errno() . ")" . $mysqli->mysqli_connect_errno());

    }

    $resultado = $mysqli->query("INSERT INTO usuarios(id_usuario, nControl, usuario, password)
    VALUES ('','$nCont','$usuario','$clave')");
}

//*********************Función para obtener los registros de los concecionarios********************//
function getEmpresa($empresa)
{
    $mysqli = new mysqli('localhost', 'root', '', 'dbcompinfra');
    if ($mysqli->connect_errno) {
        die("Fallo la conexión" . $mysqli->mysqli_connect_errno() . ")" . $mysqli->mysqli_connect_errno());

    }

    $resultado = $mysqli->query("SELECT * FROM concesionario");

    while ($fila = mysqli_fetch_array($resultado)) {
        $empresaArray [] = array('nombre' => $fila[1]);
    }

    $arrayJson = json_encode($empresaArray);

    return new soapval('return', 'xsd:string', $arrayJson);

    $resultado->close();
}

//******************************Función para obtener registro detallado de las solicitudes******************//
function getConDetail($solicitud)
{
    $mysqli = new mysqli('localhost', 'root', '', 'dbcompinfra');
    if ($mysqli->connect_errno) {
        die("Falló la conexión" . $mysqli->connect_errno() . ")" . $mysqli->connect_errno());
    }

    $resultado = $mysqli->query("SELECT * FROM concesionario_detalles ");

    while ($fila = mysqli_fetch_array($resultado)) {
        $detalles [] = array('concesionario' => $fila[0], 'Solicitud_NIS' => $fila[1], 'cable_instalar' => $fila[2], 'Tipo_Red' => $fila[4]);
    }

    $arrayJsonDetalle = json_encode($detalles);

    return new soapval('return', 'xsd:string', $arrayJsonDetalle);

    $resultado->close();
}

//si el item es igual al item mensaje de sin novedades, de lo contrario mensaje de actualizado


if (!isset($HTTP_RAW_POST_DATA))
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
$server->service($HTTP_RAW_POST_DATA);

?>
