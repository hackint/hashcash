<?php

// Usage: 	Post values of api.php?username=&password=&service=&command=&params=
// Var:		username	Description: Your NickServ username
// Var:		password	Description: Your NickServ password
// Var:		service		Description: The name of the service you wish to communicate with on IRC (ChanServ, NickServ, etc)
// Var:		command		Description: The command to use, such as "identify", or "topic"
// Var:		params		Description: Parameters, just like the way you'd pass them in IRC.

// Example usage: api.php?username=Tony&password=Blair&service=ChanServ&command=topicappend&params=#somechannel some topic information

// By Ricky Burgin (Pseudonym: Orbixx) of Exoware

require_once('./atheme.php');

// echo print_r(explode(" ", urldecode($_POST['params'])));
// echo atheme_register("127.0.0.1", 8080, "/xmlrpc", "127.0.0.1", "hashcash", "iscool")
// echo atheme_verify("127.0.0.1", 8080, "/xmlrpc", "127.0.0.1", "xmlrpcregistration", "blahfubar");


?>
