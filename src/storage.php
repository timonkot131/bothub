<?php
namespace Timonkot13\Bot;

use mysqli;
use Exception;

const USERNAME = "bot";
const PWD = "bot";
const SERVER = "localhost";
const DB = "timofey_money_bot";

function create_connection() {
	return mysqli_connect(SERVER, USERNAME, PWD, DB);
}

function close_connection($con) {
	$con->close();
}

function user_exists(mysqli $mysqli, $user) {
	$stmt = $mysqli->prepare("SELECT * FROM accounts WHERE user_name=?");
	$stmt->bind_param("s", $user);
	$stmt->execute();
	$res = $stmt->get_result();
	return $res->num_rows > 0;
}

function add_user(mysqli $mysqli, $user, $amount) {
	$stmt = $mysqli->prepare("INSERT INTO accounts (user_name, amount) VALUES (?,?)");
	$stmt->bind_param("ss", $user, $amount);
	$res = $stmt->execute();
	return $res;
}

function add_amount(mysqli $mysqli, $user, $amount) {
	try {
		$mysqli->begin_transaction();
		$stmt = $mysqli->prepare("UPDATE accounts SET amount = amount + ?
			WHERE user_name = ?");
		$stmt->bind_param("ss", $amount, $user);
		$stmt->execute();
		return $mysqli->commit();
	}
	catch(Exception $e){
		$mysqli->rollback();
		throw $e;
	}
}

function decrease_amount(mysqli $mysqli, $user, $amount) {
	try {
		$mysqli->begin_transaction();
		$stmt = $mysqli->prepare("UPDATE accounts SET amount = amount - ?
			WHERE user_name = ? AND amount >= ?");
		$stmt->bind_param("sss", $amount, $user, $amount);
		$stmt->execute();
		$mysqli->commit();
		return $stmt->affected_rows > 0;
	}
	catch(Exception $e){
		$mysqli->rollback();
		throw $e;
	}
}


function get_amount(mysqli $mysqli, $user){
	try {
		$mysqli->begin_transaction();
		$stmt = $mysqli->prepare("SELECT amount FROM accounts WHERE user_name=?");
		$stmt->bind_param("s", $user);
		$stmt->execute();
		$res = $stmt->get_result();
		$array = $res->fetch_array();
		if ($array) {
			[$amount] = $array;
			return $amount;
		} else {
			return "error_getAmount";
		}
	}
	catch(Exception $e){
		$mysqli->rollback();
		throw $e;
	}
}


?>
