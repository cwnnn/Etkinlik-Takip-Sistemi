<?php
session_start();
require_once '../baglanti.php';


if (!isset($_SESSION['kullanici_id'])) {
    header("Location: ../giris.php");
    exit;
}


$etkinlik_id = intval($_POST['etkinlik_id']);
$kullanici_id = $_SESSION['kullanici_id'];
$etkinlik_adi = trim($_POST['etkinlik_adi']);
$etkinlik_aciklama = trim($_POST['etkinlik_aciklama']);
$tarih = $_POST['etkinlik_tarihi']; 
$saat = $_POST['etkinlik_saati']; 
$durum = $_POST['durum'];
$aciklama = trim($_POST['aciklama']);
$bolum_id = $_POST['Bolumler'];


$datetime = $tarih . ' ' . $saat . ':00'; 


$stmt = $conn->prepare("SELECT * FROM etkinlikler WHERE etkinlik_id = ? AND olusturan_id = ?");
$stmt->bind_param("ii", $etkinlik_id, $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: etkinlikGuncelle.php?error=Bu etkinliği güncelleme yetkiniz yok.");
    exit;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE etkinlikler SET etkinlik_adi = ?, etkinlik_aciklamasi = ?, yeni_tarih = ?, durum = ?, iptal_nedeni = ? WHERE etkinlik_id = ?");
$stmt->bind_param("sssssi", $etkinlik_adi, $etkinlik_aciklama, $datetime, $durum, $aciklama, $etkinlik_id);

if ($stmt->execute()) {
    $stmt = $conn->prepare("UPDATE etkinlik_bolum SET bolum_id = ? WHERE etkinlik_id = ?");
    $stmt->bind_param("ii", $bolum_id, $etkinlik_id);
    $stmt->execute();
    header("Location: etkinlikGuncelle.php?success=Etkinlik başarıyla güncellendi.");
} else {
    header("Location: etkinlikGuncelle.php?error=Güncelleme sırasında hata oluştu: " . $stmt->error);
}
$stmt->close();
$conn->close();
?>
