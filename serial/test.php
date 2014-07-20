<?php
$num = "";
$num = $_GET["num"];

if($num!=""){
require './serial_class.php';
$com = "COM3";

$serial = new phpSerial();
$serial->deviceSet($com);
$serial->confBaudRate(9600);
$serial->deviceOpen();

$serial->sendMessage("$num\r");

$serial->deviceClose();
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */




?>
<a href="test.php?num=7">Prender</a>
<a href="test.php?num=4">apagar</a>