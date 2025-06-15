<?php
session_start();
require_once '../baglanti.php';
require_once "../session_kontrol.php";
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: ../giris.php");
    exit;
}

$id = $_SESSION['kullanici_id'];
$role = $_SESSION['rol'] ?? null;

// Etkinlik iptali işlemi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iptal'], $_POST['etkinlik_id'])) {
    $etkinlik_id = intval($_POST['etkinlik_id']);
    $update = $conn->prepare("UPDATE etkinlikler SET durum = 'iptal' WHERE etkinlik_id = ? AND olusturan_id = ?");
    $update->bind_param("ii", $etkinlik_id, $id);
    $update->execute();
    $update->close();
    header("Location: etkinliklerim.php");
    exit;
}

// Etkinlikleri getir
$query = "SELECT * FROM etkinlikler WHERE olusturan_id = ? AND durum != 'iptal'";
$stmt1 = $conn->prepare($query);
$stmt1->bind_param("i", $id);
$stmt1->execute();
$result1 = $stmt1->get_result();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinliklerim</title>
    <link rel="stylesheet" href="../cssDosyalari/navbar.css" />
    <link rel="stylesheet" href="../cssDosyalari/divler.css">
    <link rel="stylesheet" href="../cssDosyalari/formlar.css">
    <link rel="stylesheet" href="../cssDosyalari/tablelar.css">
</head>
<body>
<div class="navbar">
  <div class="navbar-left">
    <div class="geritusu" onclick="history.back()">
       <img class="navbar-icon" src="../resimler/geri_iconu.png" alt="Geri" />
    </div>
    <a style="font-size: 22px; font-weight: bold;">Etkinlik Takip Sistemi</a>
  </div>
  <div class="navbar-spacer"></div>
  <div class="navbar-right">
    <?php
    if ($role === 'admin') {
        echo '<a href="ogretmenEkle.php">Kullanıcı Ekle</a>';
        echo '<a href="loglar.php">Loglar</a>';
    }
    ?>
    <a href="profilim.php">Profilim</a>
    <a href="katilim.php">Katılımlar</a>
    <a href="AdminAnaSayfa.php">Etkinlik Oluştur</a>
    <a href="../logout.php">Çıkış</a>
  </div>
</div>

<div class="konteyner2" style="padding-top: 230px;">
    <table>
        <tr>
            <th>ID</th>
            <th>Etkinlik</th>
            <th>Açıklama</th>
            <th>Tarih</th>
            <th>Durum</th>
            <th>Güncelle</th>
            <th>İptal</th>
            <th>İndir</th>
        </tr>
<?php
if ($result1->num_rows > 0) {
    while ($row = $result1->fetch_assoc()) {
        echo "<tr >";
        echo "<td>" . htmlspecialchars($row['etkinlik_id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['etkinlik_adi']) . "</td>";
        echo "<td>" . htmlspecialchars($row['etkinlik_aciklamasi']) . "</td>";
        echo "<td>" . htmlspecialchars($row['etkinlik_tarihi']) . "</td>";
        echo "<td>" . htmlspecialchars($row['durum']) . "</td>";

        // Güncelle butonu (POST ile)
        echo "<td>
            <form method='post' action='etkinlikGuncelle.php'>
                <input type='hidden' name='etkinlik_id' value='" . htmlspecialchars($row['etkinlik_id']) . "'>
                <input type='submit' value='Güncelle'
                style='background-color: #a8f7b0; color: #1b4d00; border: none; cursor: pointer;' >
            </form>
        </td>";

        // İptal Et butonu
        echo "<td>
            <form method='post'>
                <input type='hidden' name='etkinlik_id' value='" . htmlspecialchars($row['etkinlik_id']) . "'>
                <input type='submit' name='iptal' value='İptal Et'
                style='background-color: #f7a8a8; color: #6b0000; border: none; cursor: pointer;' >
            </form>
        </td>";

        echo "<td>
            <form method='post' action='export_ogrenci.php'>
                <input type='hidden' name='etkinlik_id' value='" . htmlspecialchars($row['etkinlik_id']) . "'>
                <input type='submit' name='indir' value='xlsx indir'
                style='background-color:rgb(7, 110, 33); color:rgb(255, 255, 255); border: none; cursor: pointer;' >
            </form>
        </td>";
        echo "</tr>";
        

        // Katılımcılar başlığı
        echo "<tr style ='background-color: 	#D3D3D3;'><th colspan='7'>Katılımcılar</th></tr>";
        echo "<tr style='color: darkgray; background-color: 	#D3D3D3;'><th>Ad</th><th>Soyad</th><th>Sınıf</th><th>Eposta</th><th colspan='3'></th></tr>";

        // Katılımcılar
        $stmt2 = $conn->prepare("SELECT * FROM katilimlar WHERE etkinlik_id = ?");
        $stmt2->bind_param("i", $row['etkinlik_id']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            while ($katilim = $result2->fetch_assoc()) {
                $stmt3 = $conn->prepare("SELECT * FROM ogrenciler WHERE ogrenci_id = ?");
                $stmt3->bind_param("i", $katilim['ogrenci_id']);
                $stmt3->execute();
                $result3 = $stmt3->get_result();

                if ($row3 = $result3->fetch_assoc()) {
                    echo "<tr style ='background-color: 	#D3D3D3;'>";
                    echo "<td>" . htmlspecialchars($row3['ogrenci_adi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row3['ogrenci_soyadi']) . "</td>";
                    echo "<td>" . htmlspecialchars($row3['sinif']) . "</td>";
                    echo "<td>" . htmlspecialchars($row3['ogrenci_eposta']) . "</td>";
                    echo "<td colspan='3'></td>";
                    echo "</tr>";
                }
                $stmt3->close();
            }
        } 
        $stmt2->close();
    }
} else {
    echo "<tr><td colspan='7'><div class='mesaj'>Hiç etkinlik bulunamadı.</div></td></tr>";
}
$stmt1->close();
?>
    </table>
</div>
</body>
</html>
