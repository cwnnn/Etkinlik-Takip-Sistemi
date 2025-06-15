<?php
session_start();
require_once '../baglanti.php';

function temizle($veri) {
    return htmlspecialchars(trim($veri));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $eposta = temizle($_POST['username']);
    $sifre = temizle($_POST['password']);

    if ($eposta == '' || $sifre == '') {
        header("Location: giris.php?error=Lütfen tüm alanları doldurun.");
        exit();
    }

    $kullanici = mysqli_query($conn, "SELECT * FROM kullanicilar WHERE kullaniciadi='$eposta'");
    
    if ($kullanici->num_rows > 0) {
        $kullaniciVerisi = mysqli_fetch_assoc($kullanici);
        
        if ($kullaniciVerisi['sifre'] === $sifre) {
            $_SESSION['ad'] = $kullaniciVerisi['kullaniciadi'];
            $_SESSION['rol'] = $kullaniciVerisi['rol'];
            $_SESSION['kullanici_id'] = $kullaniciVerisi['kullanici_id'];

            header("Location: ../2adminS/AdminAnaSayfa.php");
            exit();
        } else {
            header("Location: ogretmen_giris.php?error=Şifre hatalı.");
            exit();
        }
    } else {
        header("Location: ogretmen_giris.php?error=Kullanıcı adı bulunamadı.");
        exit();
    }
} else {
    header("Location: ogretmen_giris.php");
    exit();
}
?>