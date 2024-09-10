<?php
namespace Timonkot13\Bot;

require_once "../vendor/autoload.php";
require "storage.php";

try {
    $bot = new \TelegramBot\Api\Client('7327244369:AAHYCG8Zje1XCzMRcyhcQTwV-sHFJ7ASPbA');
	
    $bot->on(function (\TelegramBot\Api\Types\Update $update) use ($bot) {
        $message = $update->getMessage();
		$id = $message->getChat()->getId();
		$text = $message->getText();

		$amount = str_replace(",", ".", $text);

		$conn = create_connection();
		$user = $message->getFrom()->getUsername();

		if(!user_exists($conn, $user)) {
			add_user($conn, $user, "0");
		}

		if (!is_numeric($amount)) {
			$bot->sendMessage($id, 'Пожалуйста, введите число');
			return;
		} 		

		if(bccomp($amount, "0") >= 0) {
			add_amount($conn, $user, $amount);
		} else {
			$abs_amount = str_replace("-", "", $amount);
			$is_decreased = decrease_amount($conn, $user, $abs_amount);
			if(!$is_decreased) {
				$bot->sendMessage($id, "Нехватает средств");
			}
		}
		$total = get_amount($conn, $user);
			
        $bot->sendMessage($id,  $total . "$");
		close_connection($conn);
    }, function() {return true;});
     
	$bot->run();

} catch (\TelegramBot\Api\Exception $e) {
    echo $e;
}
