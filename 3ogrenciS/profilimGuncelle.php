<?php
session_start();
require_once '../baglanti.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function temizle($veri) {
        return htmlspecialchars(trim($veri));
    }

    $ogrenci_id = $_SESSION['kullanici_id']; 

    $ad = temizle($_POST['name']);
    $soyad = temizle($_POST['surname']);
    $eposta = temizle($_POST['mail']);
    $bolum = temizle($_POST['bolumler']);
    $sinif = temizle($_POST['siniflar']);
    $sifre1 = temizle($_POST['password']);

  
    $epostaKontrol = mysqli_query($conn, "SELECT * FROM ogrenciler WHERE ogrenci_eposta = '$eposta' AND ogrenci_id != '$ogrenci_id'");
    if (mysqli_num_rows($epostaKontrol) > 0) {
        header("Location: profil.php?hata=Bu+eposta+başkası+tarafından+kullanılıyor");
        exit;
    }

    // Güncelleme sorgusu
    $sql = "UPDATE ogrenciler 
            SET ogrenci_adi = ?, ogrenci_soyadi = ?, ogrenci_eposta = ?, ogrenci_sifre = ?, bolum_id = ?, sinif = ?
            WHERE ogrenci_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssisi", $ad, $soyad, $eposta, $sifre1, $bolum, $sinif, $ogrenci_id);

    $sql2 =mysqli_query($conn,"INSERT INTO logs (ogrenci_id, hareket) VALUES ('$ogrenci_id', 'Profil güncellendi')");
    if ($stmt->execute()) {
        // Oturum bilgilerini güncelle
        $_SESSION['ad'] = $ad;
        $_SESSION['soyad'] = $soyad;
        $_SESSION['bolum_id'] = $bolum;

        header("Location: profil.php?basari=Bilgiler+güncellendi");
        exit;
    } else {
        header("Location: profil.php?hata=Güncelleme+başarısız");
        exit;
    }
}
?>
