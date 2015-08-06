<?php
require_once "common.php";
require_once "atheme.php";

if (!array_key_exists("username", $_GET)) {
    error("Enter an accoutname.");
}
if (!array_key_exists("password", $_GET)) {
    error("Enter a password.");
}
if (!session_start()) {
    error("Internal Error");
}
if (!(array_key_exists("purchased", $_SESSION) && $_SESSION["purchased"])) {
    error("Missing the proof-of-work.");
}
if (array_key_exists("registered", $_SESSION) && $_SESSION["registered"]) {
    error("Already registered.");
}

$nickname = $_GET["username"];
$password = $_GET["password"];

if (preg_match("/^[a-z]+$/", $nickname)) {
} else {
    error("You may only use non-capital letters for your nickname");
}

if (strlen($nickname) > 20) {
    error("Name too long");
}

$resp = atheme_register("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, "some@anonymous-user.yeah");

if (strpos($resp, 'Registration successful') !== FALSE) {
    # assume vhost
    atheme("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, "HostServ", "TAKE", array('hackint/user/$account'));

    ok("Registration successful:http://www.hackint.org/");
    $_SESSION["registered"] = True;
} else {
    error($resp);
}
