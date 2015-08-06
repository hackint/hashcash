<?php

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
