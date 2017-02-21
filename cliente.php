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

$myResultado = $client->call('loginUser', array('usuario' => 'alexis', 'clave' => 'alexis25'));
//$mynewUsuario = $client->call('newUser', array('nControl'=>'12345', 'usuario'=>'sebast', 'clave'=>'1q2w3e4r5t'));
$myEmpresa = $client->call('getEmpresas', array('empresa' => ''));
$myNisDetails = $client->call('getNisDetails', array('nombre' => '', 'solNis' => '', 'cable' => '', 'fase' => '', 'tipoRed' => ''));

echo "Resultado: " . $myResultado;

echo "<br>";

//echo "Usuario nuevo: ".$mynewUsuario;

echo "<br>";

echo "Empresas: " . $myEmpresa;

echo "<br><br>";

echo "Solicitud detallada: " . $myNisDetails;


?>