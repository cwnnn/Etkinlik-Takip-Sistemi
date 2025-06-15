<?php
session_start();
require_once '../baglanti.php';

// Kullanıcı oturum kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: ../giris.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: AdminAnaSayfa.php");
    exit;
}

// Formdan gelen veriler
$etkinlik_adi = trim($_POST['etkinlik_adi'] ?? '');
$etkinlik_aciklama = trim($_POST['etkinlik_aciklama'] ?? '');
$etkinlik_tarihi = $_POST['etkinlik_tarihi'] ?? '';
$etkinlik_saati = $_POST['etkinlik_saati'] ?? '';
$durum = $_POST['durum'] ?? 'aktif';
$bolum_id = intval($_POST['Bolumler'] ?? 0);
$olusturan_id = $_SESSION['kullanici_id'];

// Temel doğrulama
if (
    empty($etkinlik_adi) ||
    empty($etkinlik_aciklama) ||
    empty($etkinlik_tarihi) ||
    empty($etkinlik_saati) ||
    $bolum_id === 0
) {
    $error = urlencode("Lütfen tüm alanları doldurun ve bir bölüm seçin.");
    header("Location: AdminAnaSayfa.php?error=$error");
    exit;
}

// Tarih ve saat birleştirme
$etkinlik_datetime = $etkinlik_tarihi . ' ' . $etkinlik_saati . ':00';

// Etkinlik ekleme
$stmt = $conn->prepare("INSERT INTO `etkinlikler`(`etkinlik_adi`, `etkinlik_aciklamasi`, `etkinlik_tarihi` , `durum`, `olusturan_id`) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $etkinlik_adi, $etkinlik_aciklama, $etkinlik_datetime, $durum, $olusturan_id);

if ($stmt->execute()) {
    $etkinlik_id = $stmt->insert_id;
    $stmt->close();

    // Bölüm ile ilişkilendir
    $stmt2 = $conn->prepare("INSERT INTO etkinlik_bolum (etkinlik_id, bolum_id) VALUES (?, ?)");
    $stmt2->bind_param("ii", $etkinlik_id, $bolum_id);
    $stmt2->execute();
    $stmt2->close();

    // Başarı mesajı ile yönlendir
    $success = urlencode("Etkinlik başarıyla eklendi.");
    header("Location: AdminAnaSayfa.php?success=$success");
    exit;

} else {
    $error = urlencode("Etkinlik eklenirken bir hata oluştu: " . $conn->error);
    header("Location: AdminAnaSayfa.php?error=$error");
    exit;
}
?>