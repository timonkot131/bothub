<?php
namespace Timonkot13\Bot;

require_once "../vendor/autoload.php";

$URL = "https://c447-2a00-1fa1-b040-b0b4-9495-4bec-2770-3552.ngrok-free.app/";
$CLIENT = "7327244369:AAHYCG8Zje1XCzMRcyhcQTwV-sHFJ7ASPbA";

try {
    $bot = new \TelegramBot\Api\Client($CLIENT);
	$bot->setWebhook($URL);
	echo "Well done";
} catch (\TelegramBot\Api\Exception $e) {
    echo $e;
}
?>
