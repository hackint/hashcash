<?php
	require_once('./atheme.php');
	function res($msg) {
		header("Content-Type", "text/plain");
		die("error:$msg");
	}
	function resok($msg, $whereto) {
		header("Content-Type", "text/plain");
		die("ok:$msg:$whereto");
	}
	if (!array_key_exists("username", $_GET)) {
		res("say username");
	}
	if (!array_key_exists("password", $_GET)) {
		res("say password");
	}
	if (!session_start()) {
		res("error");
	}
	if (!(array_key_exists("purchased", $_SESSION) && $_SESSION["purchased"])) {
		res("you haven't done the hashcash calculation");
	}
	if (array_key_exists("registered", $_SESSION) && $_SESSION["registered"]) {
		res("no");
	}


	$nickname = $_GET["username"];
	$password = $_GET["password"];

	$res = atheme_register("127.0.0.1", 8080, "/xmlrpc", "127.0.0.1", $nickname, $password, "some@anonymous-user.yeah");

	if (strpos($res, 'Registration successful') !== FALSE) {
		echo "ok:";
		echo "Registration successful";
		echo ":http://www.hackint.org/";
		$_SESSION["registered"] = True;
	} else {
		res($res);
	}
?>
