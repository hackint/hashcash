<?php
session_start();

// generate a fresh session
session_regenerate_id(true);

header("location: /hashcash/");