<?php
	function res($msg) {
		header("Content-Type", "text/plain");
		die("error:$msg");
	}
	function resok($msg) {
		header("Content-Type", "text/plain");
		die("ok:$msg");
	}
	if (!session_start()) {
		res("error");
	}
	if (!array_key_exists("action", $_GET)) {
		res("what");
	}
	if (array_key_exists("disqualified", $_SESSION) &&
		$_SESSION["disqualified"]) {
		res("plonk");
	}
	if (array_key_exists("purchased", $_SESSION) && $_SESSION["purchased"]) {
		res("already ok");
	}
	$action = $_GET["action"];
	if ($action == "order") {
		if (array_key_exists("secret", $_SESSION)) {
			res("come back later");
		}
		$salt = hash("sha256", openssl_random_pseudo_bytes(1024));
		$secret = rand(1, 1000000);
		$uuaa = "$salt$secret";
		for ($i = 0; $i < 999; $i++) {
			$uuaa = hash("sha256", $uuaa);
		}
		$_SESSION["secret"] = $secret;
		resok($uuaa);
	} else if ($action == "purchase") {
		if (!array_key_exists("secret", $_SESSION)) {
			res("no");
		}
		if (!array_key_exists("secret", $_GET)) {
			res("yes");
		}
		$secret = $_SESSION["secret"];
		if ("$secret" == $_GET["secret"]) {
			$_SESSION["purchased"] = True;
			resok("purchase ok");
		} else {
			$_SESSION["disqualified"] = True;
			res("plonking");
		}
	}
?>
