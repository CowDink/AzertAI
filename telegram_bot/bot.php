<?php
$token = "rahasia";

require_once("../class/autoload.php");

$azert = new AI("TelegramBot");

$azert->ai()->token($token);

$azert->ai()->method("wh");

$azert->ai()->autoData();

$azert->ai()->process();

$azert->ai()->send();
?>