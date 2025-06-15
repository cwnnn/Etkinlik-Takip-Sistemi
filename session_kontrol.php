<?php
session_start();

if (isset($_SESSION['last_activity'])) {
    $inactive = 120; 
    $session_life = time() - $_SESSION['last_activity'];

    if ($session_life > $inactive) {
        session_unset();
        session_destroy();
        header("Location: ../1giris/girisSecme.php");
        exit();
    }
}

$_SESSION['last_activity'] = time();

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: ../1giris/girisSecme.php");
    exit();
}
?>