<?php
$token = "442684257:AAEk1hjxPQAaYvkqhYTvbr1y6qLn9GJ-hso";
$web = "https://api.telegram.org/bot".$token;

require_once("../class/autoload.php");

$data = file_get_contents("php://input");

$dataArr = json_decode($data, true);

$chatId = $dataArr["message"]["chat"]["id"];
$chatText = (string)$dataArr["message"]["text"];
$chatText = str_replace("@AzertBot", "", $chatText);

$azert = new AI("TelegramBot");

$azert->ai()->setMessage($chatText);
$azert->ai()->processMessage();
$paket = urlencode($azert->ai()->getResponse());

file_get_contents($web."/sendmessage?chat_id={$chatId}&text={$paket}");

?>