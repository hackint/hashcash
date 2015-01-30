<?php
	require_once('./atheme.php');
	function res($msg) {
		header("Content-Type", "text/plain");
		die("error:$msg");
	}
	function resok($msg) {
		header("Content-Type", "text/plain");
		die("ok:$msg");
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
//		res("hmm");
	}
	if (array_key_exists("registered", $_SESSION) && $_SESSION["registered"]) {
//		res("no");
	}
	$params = array($_GET["password"], "this-is-a-tor-user@hackint.org");
	echo atheme_nologin("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $_GET["username"], "NickServ", "register", $params);
	$_SESSION["registered"] = True;
?>
