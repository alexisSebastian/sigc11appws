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
$server->configureWSDL('WsSIG11APP', $ns);
$server->wsdl->schemaTargetNamespace = $ns;

//********************Se registra el menú*****************//
$server->register('loginUser', array('usuario' => 'xsd:string', 'clave' => 'xsd:string'), array('return' => 'xsd:int'), $ns);

$server->register('newUser', array('nControl' => 'xsd:int', 'usuario' => 'xsd:string', 'clave' => 'xsd:string'),
    array('return' => 'xsd:int'), $ns);

$server->register('getEmpresas', array('empresa' => 'string'), array('return' => 'xsd:string'), $ns);

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
function newUser($nControl, $usuario, $clave)
{
    $mysqli = new mysqli('localhost', 'root', '', 'users_sigc');

    if ($mysqli->connect_errno) {
        die("Fallo la conexión" . $mysqli->mysqli_connect_errno() . ")" . $mysqli->mysqli_connect_errno());

    }

    $resultado = $mysqli->query("INSERT INTO usuarios (nControl, usuario, password) VALUES ('', '$nControl', '$usuario', '$clave')");
    $resultado->close();
}

//*********************Función para obtener los registros de los concecionarios********************//
function getEmpresas($empresa)
{
    $mysqli = new mysqli('localhost', 'root', '', 'dbcompinfra');
    if ($mysqli->connect_errno) {
        die("Fallo la conexión" . $mysqli->mysqli_connect_errno() . ")" . $mysqli->mysqli_connect_errno());

    }

    $resultado = $mysqli->query("SELECT * FROM concesionario ORDER BY nombre");

    while ($fila = mysqli_fetch_array($resultado)) {
        $empresaArray [] = array('nombre' => $fila[1]);
    }

    $arrayJson = json_encode($empresaArray);

    return new soapval('return', 'xsd:string', $arrayJson);

    $resultado->close();
}

function getDetailNis()
{
    $mysqli = new mysqli('localhost', 'root', '', 'dbcompinfra');
    if ($mysqli->connect_errno) {
        die("Fallo la conexión" . $mysqli->mysqli_connect_errno() . ")" . $mysqli->mysqli_connect_errno());

    }

    $resultado = $mysqli->query("select nombre as concesionario,num_solicitud_nis 
    as Solicitud_NIS, cable_instalar, fase, tipoRedDescripcion 
    as Tipo_Red from solicitud as sol inner join concesionario 
    as cs on sol.idConcesionario = cs.id inner join tipored as tr on sol.idtipored = tr.idtipoRed
");

    while ($fila = mysqli_fetch_array($resultado)) {
        $empresaArray [] = array('nombre' => $fila[1]);
    }

    $arrayJson = json_encode($empresaArray);

    return new soapval('return', 'xsd:string', $arrayJson);

    $resultado->close();
}

if (!isset($HTTP_RAW_POST_DATA))
    $HTTP_RAW_POST_DATA = file_get_contents('php://input');
$server->service($HTTP_RAW_POST_DATA);

?>