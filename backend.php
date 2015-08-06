<?php
require_once "common.php";

$rounds = 99;

if (!session_start()) {
    error("cannot start session");
}
if (!array_key_exists("action", $_GET)) {
    error("what are you trying to achieve?");
}
if (array_key_exists("disqualified", $_SESSION) && $_SESSION["disqualified"]) {
    error("please delete your session cookie");
}
if (array_key_exists("purchased", $_SESSION) && $_SESSION["purchased"]) {
    error("please delete your session cookie and restart");
}

switch($_GET["action"]) {
    case "order":
        if (array_key_exists("secret", $_SESSION)) {
            error("please delete your session cookie and restart");
        }
        $salt = hash("sha1", openssl_random_pseudo_bytes(1024));
        $secret = rand(1, 100000); // workSize
        $uuaa = "$salt$secret";
        for ($i = 0; $i < $rounds; $i++) {
            $uuaa = hash("sha256", $uuaa);
        }
        $_SESSION["secret"] = $secret;
        ok("$salt;$uuaa;$rounds");
        break;

    case "purchase":
        if (!array_key_exists("secret", $_SESSION)) {
            error("some error 1230 has happened");
        }
        if (!array_key_exists("secret", $_GET)) {
            error("what are you doing");
        }
        $secret = $_SESSION["secret"];
        if ("$secret" == $_GET["secret"]) {
            $_SESSION["purchased"] = True;
            ok("purchase ok");
        } else {
            $_SESSION["disqualified"] = True;
            error("strange things are going on, please start from scratch, thanks");
        }
        break;
}
