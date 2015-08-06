<?php

$RPC_HOST = "127.0.0.1";
$RPC_PORT = 8080;

$HASHCASH_DEFAULT_EMAIL = "some@anonymous-user.yeah";
$HASHCASH_ROUNDS = 99;


$ACCOUTNAME_MAX_LEN = 20;


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
