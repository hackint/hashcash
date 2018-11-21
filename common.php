<?php

$RPC_HOST = "[::1]";
$RPC_PORT = 8080;

$HASHCASH_BASE_URL = '/hashcash';

$HASHCASH_DEFAULT_EMAIL = "hashcash@anonymous.user";
$HASHCASH_ROUNDS = 99;

$ACCOUTNAME_MAX_LEN = 20;

$ERROR_SESSION_INIT = "Internal Error";
$ERROR_ILLEGAL_METHOD = "Illegal Method";
$ERROR_MISSING_ARGS = "Missing Arguments";
$ERROR_ACTIVE_SESSION = "There is an active session. <a href=\"reset.php\">Clear it</a>?";
$ERROR_MISSING_SECRET = "Session was not properly initalized with a secret, <a href=\"reset.php\">try again</a>.";

$ERROR_MISSING_PROOF = "Proof is missing. <a href=\"reset.php\">Retry</a>?";
$ERROR_PROOF_ALREADY_CONFIRMED = "Proof was already confirmed, <a href=\"reset.php\">start fresh</a>?";
$ERROR_PROOF_INCORRECT = "Proof was incorrect, <a href=\"reset.php\">try again</a>.";

$ERROR_ACCOUNTNAME_MISSING = "Enter an accoutname.";
$ERROR_ACCOUNTNAME_MAXLEN = "Your accoutname should not be longer than $ACCOUTNAME_MAX_LEN characters.";
$ERROR_ACCOUNTNAME_CASE = "Your accountname has to be lowercase letters.";

$ERROR_PASSWORD_MISSING = "Enter a password.";


function error($msg)
{
    header("Content-Type", "text/plain");
    die("error:$msg");
}

function ok($msg)
{
    header("Content-Type", "text/plain");
    die("ok:$msg");
}
