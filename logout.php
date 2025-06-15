<?php
session_start();
session_unset();  // tüm oturum değişkenlerini sil
session_destroy(); // oturumu yok et
header("Location: 1giris/girisSecme.php"); // giriş sayfasına yönlendir
exit();
?>
