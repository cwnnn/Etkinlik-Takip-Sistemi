<?php
session_start();
require_once '../baglanti.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function temizle($veri) {
        return htmlspecialchars(trim($veri));
    }

    $kullanici_id = $_SESSION['kullanici_id']; 

    $ad = temizle($_POST['username']);
    $rol = $_SESSION['rol'];
    $sifre1 = temizle($_POST['password']);

  
    $varMi = mysqli_query($conn, "SELECT * FROM kullanicilar WHERE kullaniciadi = '$ad'");
    if ($varMi && mysqli_num_rows($varMi) > 0) {
        header("Location: profilim.php?hata=Kullanici+zaten+kayitli");
        exit;
    }


    $sql = "UPDATE kullanicilar
            SET kullaniciadi = ?, sifre = ?
            WHERE kullanici_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $ad, $sifre1, $kullanici_id);

    if ($stmt->execute()) {
    
        $_SESSION['ad'] = $ad;

        header("Location: profilim.php?basari=Bilgiler+güncellendi");
        exit;
    } else {
        header("Location: profilim.php?hata=Güncelleme+başarısız");
        exit;
    }
}
?>
