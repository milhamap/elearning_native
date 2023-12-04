<?php
    session_start();
    $SESSION = [];
    session_unset();
    session_destroy();

    setcookie('nrp', '', time()-60);
    setcookie('key', '', time()-60);

    header("Location: ../index.php");
    exit;
?>