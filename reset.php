<?php
session_start();

// force a fresh session
session_destroy();

header("location: /hashcash/");