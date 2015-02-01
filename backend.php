<?php
	$NUM_ROUNDS = 99;
	function res($msg) {
		header("Content-Type", "text/plain");
		die("error:$msg");
	}
	function resok($msg) {
		header("Content-Type", "text/plain");
		die("ok:$msg");
	}
	if (!session_start()) {
		res("cannot start session");
	}
	if (!array_key_exists("action", $_GET)) {
		res("what are you trying to achieve?");
	}
	if (array_key_exists("disqualified", $_SESSION) &&
		$_SESSION["disqualified"]) {
		res("please delete your session cookie");
	}
	if (array_key_exists("purchased", $_SESSION) && $_SESSION["purchased"]) {
		res("please delete your session cookie and restart");
	}
	$action = $_GET["action"];
	if ($action == "order") {
		if (array_key_exists("secret", $_SESSION)) {
			res("please delete your session cookie and restart");
		}
		$salt = hash("sha1", openssl_random_pseudo_bytes(1024));
		$secret = rand(1, 1000000);
		$uuaa = "$salt$secret";
		for ($i = 0; $i < $NUM_ROUNDS; $i++) {
			$uuaa = hash("sha256", $uuaa);
		}
		$_SESSION["secret"] = $secret;
		resok("$salt;$uuaa;$NUM_ROUNDS");
	} else if ($action == "purchase") {
		if (!array_key_exists("secret", $_SESSION)) {
			res("some error 1230 has happened");
		}
		if (!array_key_exists("secret", $_GET)) {
			res("what are you doing");
		}
		$secret = $_SESSION["secret"];
		if ("$secret" == $_GET["secret"]) {
			$_SESSION["purchased"] = True;
			resok("purchase ok");
		} else {
			$_SESSION["disqualified"] = True;
			res("strange things are going on, please start from scratch, thanks");
		}
	}
?>
