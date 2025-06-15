
<?php
require_once '../baglanti.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function temizle($veri) {
        return htmlspecialchars(trim($veri));
    }

    $kullanici_adi = temizle($_POST['username']);
    $rol = temizle($_POST['roller']);
    $sifre = temizle($_POST['password']);



    $varMi = mysqli_query($conn, "SELECT * FROM kullanicilar WHERE kullaniciadi = '$kullanici_adi'");
    if ($varMi && mysqli_num_rows($varMi) > 0) {
        header("Location: ogretmenEkle.php?hata=Kullanici+zaten+kayitli");
        exit;
    }

    $sql = "INSERT INTO kullanicilar (kullaniciadi, rol, sifre)
            VALUES ('$kullanici_adi', '$rol', '$sifre')";

     if (mysqli_query($conn, $sql)) {
        header("Location: ogretmenEkle.php?basari=Kayit+basarili");
} else {
        header("Location: ogretmenEkle.php?hata=Kayit+basarisiz");
        exit;
    }
}
?>