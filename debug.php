<?php
require_once("class/autoload.php");

$azert = new AI;

$azert->setMessage("/help");
$azert->processMessage();
$paket = $azert->getResponse();
$chatType = $azert->getType();
$mess = $azert->message;
$fc = $mess[0];

print_r($fc);
?>