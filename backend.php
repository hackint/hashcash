<?php
require_once "common.php";


if (!session_start()) {
    error($ERROR_SESSION_INIT);
}

// Security
if (empty($_GET['action'])) {
    error($ERROR_MISSING_ARGS);
}
if (!empty($_SESSION['disqualified'])) {
    error($ERROR_PROOF_INCORRECT);
}
if (!empty($_SESSION['purchased'])) {
    error($ERROR_PROOF_ALREADY_CONFIRMED);
}

// Action Router
switch ($_GET["action"]) {
    case "ping":
        ok('pong');
        break;

    case "order":
        // Secret Initialization
        if (!empty($_SESSION['secret'])) {
            error($ERROR_ACTIVE_SESSION);
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
        // Proof Delivery and Validation
        if (empty($_SESSION['secret'])) {
            error($ERROR_MISSING_SECRET);
        }
        if (empty($_GET['secret'])) {
            error($ERROR_MISSING_ARGS);
        }

        if ($_SESSION["secret"] == $_GET['secret']) {
            $_SESSION["purchased"] = true;
            ok("purchase ok");
        } else {
            $_SESSION["disqualified"] = true;
            error($ERROR_PROOF_INCORRECT);
        }
        break;
}
