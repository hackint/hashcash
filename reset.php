<?php
session_start();

// force a fresh session
session_destroy();

header("location: $HASHCASH_BASE_URL/");