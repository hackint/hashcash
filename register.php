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
		res("already registered");
	}


	$nickname = $_GET["username"];
	$password = $_GET["password"];

	if (preg_match("/^[a-z]+$/", $nickname)) {
	} else {
		res("You may only use non-capital letters for your nickname");
	}

	if (strlen($nickname) > 20) {
		res("Nick too long");
	}

	$res = atheme_register("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, "some@anonymous-user.yeah");


	if (strpos($res, 'Registration successful') !== FALSE) {
		atheme("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, "HostServ", "TAKE", array('hackint/user/$account'));
		echo "ok:";
		echo "Registration successful";
		echo ":http://www.hackint.org/";
		$_SESSION["registered"] = True;
	} else {
		res($res);
	}
?>
