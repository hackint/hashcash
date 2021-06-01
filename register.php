<?php
require_once "common.php";
require_once "atheme.php";

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    error($ERROR_ILLEGAL_METHOD);
}

if (!session_start()) {
    error($ERROR_SESSION_INIT);
}

// Form Validation
if (empty($_POST['username'])) {
    error($ERROR_ACCOUNTNAME_MISSING);
} else {
    if (!preg_match("/^[a-z]+$/", $_POST['username'])) {
        error($ERROR_ACCOUNTNAME_CASE);
    } elseif (strlen($_POST['username']) > $ACCOUTNAME_MAX_LEN) {
        error($ERROR_ACCOUNTNAME_MAXLEN);
    }
}

if (empty($_POST['password'])) {
    error($ERROR_PASSWORD_MISSING);
}

$nickname = $_POST["username"];
$password = $_POST["password"];

// Security
if (empty($_SESSION['purchased'])) {
    // proof not yet delivered
    error($ERROR_MISSING_PROOF);
}
if (!empty($_SESSION['registered'])) {
    // proof was already used to register an account
    session_destroy();
    header("location: $HASHCASH_BASE_URL/");
}

// Register Account
$resp = atheme_register($RPC_HOST, $RPC_PORT, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, $HASHCASH_DEFAULT_EMAIL);

if (strpos($resp, 'Registration successful') !== FALSE) {
    $_SESSION["registered"] = True;

    // Assume VHost
    atheme("127.0.0.1", 8080, "/xmlrpc", $_SERVER['REMOTE_ADDR'], $nickname, $password, "HostServ", "TAKE", array('hackint/user/$account'));

    // Destroy session to prevent proof reusal
    session_destroy();

    ok("Registration successful:https://www.hackint.org/");
} else {
    error($resp);
}
