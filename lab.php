<?php
require_once("class/autoload.php");

$token = "rahasia";

$lab = new AI("TelegramBot");

$lab->ai()->name("@AzertBot");

$lab->ai()->token($token);

$lab->ai()->method("wh");

//$lab->ai()->autoData();

$lab->ai()->setChatId("999990");

$lab->ai()->setMessage("kambing");

$lab->ai()->process();

$lab->ai()->echoAll();

?>