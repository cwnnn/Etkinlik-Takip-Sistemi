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

    $kullanici = mysqli_query($conn, "SELECT * FROM ogrenciler WHERE ogrenci_eposta='$eposta'");
    
    if ($kullanici->num_rows > 0) {
        $kullaniciVerisi = mysqli_fetch_assoc($kullanici);
        
        if ($kullaniciVerisi['ogrenci_sifre'] === $sifre) {
            $_SESSION['ad'] = $kullaniciVerisi['ogrenci_adi'];
            $_SESSION['soyad'] = $kullaniciVerisi['ogrenci_soyadi'];
            $_SESSION['kullanici_id'] = $kullaniciVerisi['ogrenci_id'];
             $_SESSION['bolum_id'] = $kullaniciVerisi['bolum_id'];
             
            header("Location: ../3ogrenciS/AnaSayfa.php");
            exit();
        } else {
            header("Location: ogrenci_giris.php?error=Şifre hatalı.");
            exit();
        }
    } else {
        header("Location: ogrenci_giris.php?error=E-posta bulunamadı.");
        exit();
    }
} else {
    header("Location: ogrenci_giris.php");
    exit();
}
?>