<?php
require_once("class/autoload.php");

$token = "token rahasia :v";

$lab = new AI("TelegramBot");

$lab->ai()->token($token);

$lab->ai()->method("wh");

//$lab->ai()->autoData();

$lab->ai()->setChatId("99999");

$lab->ai()->setMessage("help @sywgg _-\n yosh WojksKsk  --;#&+#($/&)!' Www");

$lab->ai()->process();

$lab->ai()->echoAll();

$text = "dia adalah raja";
print_r(explode(" ", $text));

?>