<?php
require_once "common.php";
require_once "atheme.php";

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    error("Illegal Request Method");
}

if (!session_start()) {
    error("Internal Error");
}

// Form Validation
if (empty($_POST['username'])) {
    error("Enter an accoutname.");
} else {
    if (!preg_match("/^[a-z]+$/", $_POST['username'])) {
        error("Your accountname has to be lowercase.");
    } elseif (strlen($_POST['username']) > $ACCOUTNAME_MAX_LEN) {
        error("Accoutname too long.");
    }
}

if (empty($_POST['password'])) {
    error("Enter a password.");
}

$nickname = $_POST["username"];
$password = $_POST["password"];

// Security
if (!empty($_SESSION['purchased']) && (bool) $_SESSION["purchased"]) {
    error("Missing the proof-of-work.");
}
if (!empty($_SESSION['registered']) && (bool) $_SESSION["registered"]) {
    error("Already registered.");
}

// Register Account
$resp = atheme_register($RPC_HOST, $RPC_PORT, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, $HASHCASH_DEFAULT_EMAIL);

if (strpos($resp, 'Registration successful') !== FALSE) {
    $_SESSION["registered"] = True;

    // Assume VHost
    atheme("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, "HostServ", "TAKE", array('hackint/user/$account'));

    ok("Registration successful:http://www.hackint.org/");
} else {
    error($resp);
}
