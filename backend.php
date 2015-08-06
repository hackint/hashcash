<?php
require_once "common.php";


if (!session_start()) {
    error("Internal Error");
}

// Security
if (empty($_GET['action'])) {
    error("Illegal Argumnent");
}
if (!empty($_SESSION['disqualified']) && (bool) $_SESSION['disqualified']) {
    error("Proof was incorrect, <a href=\"reset.php\">try again</a>.");
}
if (!empty($_SESSION['purchased']) && (bool) $_SESSION['purchased']) {
    error("Proof was already used to create an account.");
}

// Action Router
switch($_GET["action"]) {
    case "order":
        if (!empty($_SESSION['secret'])) {
            error("There is an active session. <a href=\"reset.php\">Clear it</a>?");
        }

        $salt = hash("sha1", openssl_random_pseudo_bytes(1024));
        $secret = rand(1, 100000); // workSize
        $uuaa = "$salt$secret";
        for ($i = 0; $i < $HASHCASH_ROUNDS; $i++) {
            $uuaa = hash("sha256", $uuaa);
        }
        $_SESSION["secret"] = $secret;
        ok("$salt;$uuaa;$HASHCASH_ROUNDS");
        break;

    case "purchase":
        if (empty($_SESSION['secret'])) {
            error("Session was not properly initalized with a secret, <a href=\"reset.php\">try again</a>.");
        }
        if (empty($_GET['secret'])) {
            error("Missing Arguments");
        }

        if ($_SESSION["secret"] == $_GET['secret']) {
            $_SESSION["purchased"] = true;
            ok("purchase ok");
        } else {
            $_SESSION["disqualified"] = true;
            error("Proof was incorrect, <a href=\"reset.php\">try again</a>.");
        }
        break;
}
