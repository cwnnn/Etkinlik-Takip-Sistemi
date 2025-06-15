
<?php
require_once '../baglanti.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function temizle($veri) {
        return htmlspecialchars(trim($veri));
    }

    $ad = temizle($_POST['name']);
    $soyad = temizle($_POST['surname']);
    $eposta = temizle($_POST['mail']);
    $bolum = temizle($_POST['bolumler']);
    $sinif = temizle($_POST['siniflar']);
    $sifre1 = temizle($_POST['password']);
    $sifre2 = temizle($_POST['password2']);

    // Hata varsa mesajla geri yönlendir
    if ($sifre1 !== $sifre2) {
        header("Location: kayitOl.php?hata=Sifreler+eslesmiyor");
        exit;
    }



    $varMi = mysqli_query($conn, "SELECT * FROM ogrenciler WHERE ogrenci_eposta = '$eposta'");
    if ($varMi && mysqli_num_rows($varMi) > 0) {
        header("Location: kayitOl.php?hata=Eposta+zaten+kayitli");
        exit;
    }

    $sql = "INSERT INTO ogrenciler (ogrenci_adi, ogrenci_soyadi, ogrenci_eposta, ogrenci_sifre, bolum_id, sinif)
            VALUES ('$ad', '$soyad', '$eposta', '$sifre1', '$bolum', '$sinif')";

     if (mysqli_query($conn, $sql)) {
        header("Location: kayitOl.php?basari=Kayit+basarili");

        $kullanici = mysqli_query($conn, "SELECT * FROM ogrenciler WHERE ogrenci_eposta='$eposta'");
    
    if ($kullanici->num_rows > 0) {
        $kullaniciVerisi = mysqli_fetch_assoc($kullanici);
        
            $_SESSION['ad'] = $kullaniciVerisi['ogrenci_adi'];
            $_SESSION['soyad'] = $kullaniciVerisi['ogrenci_soyadi'];
            $_SESSION['kullanici_id'] = $kullaniciVerisi['ogrenci_id'];
            $_SESSION['bolum_id'] = $kullaniciVerisi['bolum_id'];

            header("Location: ../3ogrenciS/AnaSayfa.php");
            exit();

        
    }
} else {
        header("Location: kayitOl.php?hata=Kayit+basarisiz");
        exit;
    }
}
?>
