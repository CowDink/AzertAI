<?php
$token = "token rahasia :v";
$web = "https://api.telegram.org/bot".$token;

$data = file_get_contents("php://input");

$dataArr = json_decode($data, true);

$chatId = $dataArr["message"]["chat"]["id"];
$chatText = (string)$dataArr["message"]["text"];
$chatText = str_replace("@AzertBot", "", $chatText);

file_get_contents($web."/sendmessage?chat_id={$chatId}&text={$chatText}");

file_get_contents($web."/sendmessage?chat_id={$chatId}&text={pesan diterima}");

require_once("../class/autoload.php");

$azert = new AI("TelegramBot");

$azert->ai()->token($token);

$azert->ai()->method("wh");

$azert->ai()->autoData();

$azert->ai()->process();

$azert->ai()->send();

file_get_contents($web."/sendmessage?chat_id={$chatId}&text={$azert->ai()->getType()}");
?>