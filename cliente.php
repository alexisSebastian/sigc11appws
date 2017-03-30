<?php
/**
 * Created by IntelliJ IDEA.
 * User: sebastian
 * Date: 20/02/17
 * Time: 18:40
 */
require_once("lib/nusoap.php");
$wsdl = "http://localhost/sigc11appws/servidor.php?wsdl";
$client = new nusoap_client($wsdl, 'wsdl');

$myResultado = $client->call('loginUser', array('usuario' => '', 'clave' => ''));
//$mynewUsuario = $client->call('newUser', array('nControl' => '', 'usuario' => '', 'clave' => ''));
$myEmpresa = $client->call('getEmpresa', array('empresa' => ''));

$myDetalle = $client->call('getConDetail', array('solicitud' => ''));

echo "Resultado: " . $myResultado;

echo "<br>";

//echo "Usuario nuevo: ".$mynewUsuario;

echo "<br>";

echo $myEmpresa;

echo "<br><br>";

echo $myDetalle;

?>
