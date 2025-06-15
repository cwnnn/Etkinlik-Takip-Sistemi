<?php
session_start();
include '../baglanti.php';
require_once "../session_kontrol.php";
if (isset($_SESSION['kullanici_id'])) {
    $id = $_SESSION['kullanici_id'];
}
if (isset($_SESSION['bolum_id'])) {
    $bolumID = $_SESSION['bolum_id'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['dugme']) && isset($_POST['id'])) {
    $etkinlik_id = $_POST['id'];

    if ($_POST['dugme'] == 'Etkinliğe Katıl') {
        $kontrol = mysqli_query($conn, "SELECT * FROM katilimlar WHERE etkinlik_id='$etkinlik_id' AND ogrenci_id='$id'");
        if (mysqli_num_rows($kontrol) == 0) {
            $ekle = mysqli_query($conn, "INSERT INTO katilimlar (etkinlik_id, ogrenci_id) VALUES ('$etkinlik_id', '$id')");
            $sql = mysqli_query($conn, "INSERT INTO logs (ogrenci_id, hareket) VALUES ('$id', 'Etkinliğe($etkinlik_id) katildi')");
        }
    } elseif ($_POST['dugme'] == 'Etkinlikten Çık') {
        $sil = mysqli_query($conn, "DELETE FROM katilimlar WHERE etkinlik_id='$etkinlik_id' AND ogrenci_id='$id'");
        $sql = mysqli_query($conn, "INSERT INTO logs (ogrenci_id, hareket) VALUES ('$id', 'Etkinlikten($etkinlik_id) çıktı.')");
    }

    header("Location: AnaSayfa.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssDosyalari/navbar.css" />
    <link rel="stylesheet" href="../cssDosyalari/divler.css">
    <link rel="stylesheet" href="../cssDosyalari/formlar.css">
    <link rel="stylesheet" href="../cssDosyalari/tablelar.css">
    <title>Etkinlik Takip Sistemi</title>
</head>
<body>
<div class="navbar">
    <div class="navbar-left">
        <div class="geritusu" onclick="history.back()" aria-label="Geri dön">
            <img class="navbar-icon" src="../resimler/geri_iconu.png" alt="Geri" />
        </div>
        <a style="font-size: 22px; font-weight: bold;">Etkinlik Takip Sistemi</a>
    </div>
    <div class="navbar-spacer"></div>
    <div class="navbar-right">
        <?php
        $bildirim_sql = "SELECT COUNT(*) as sayi FROM bildirimler WHERE ogrenci_id = ? AND okundu_mu = 0";
        $stmt_bildirim = $conn->prepare($bildirim_sql);
        $stmt_bildirim->bind_param("i", $id);
        $stmt_bildirim->execute();
        $result_bildirim = $stmt_bildirim->get_result();
        $row_bildirim = $result_bildirim->fetch_assoc();
        $bildirim_var = ($row_bildirim['sayi'] > 0);
        $icon_src = $bildirim_var ? "../resimler/mesaj-geldi.png" : "../resimler/mesajj-yok.png";
        ?>
        <img onclick="window.location.href='bildirimler.php'" class="navbar-icon" src="<?php echo $icon_src; ?>" style="width: 40px; height: 40px;" alt="">
        <a href="profil.php">Profilim</a>
        <a href="AnaSayfa.php">Etkinliklerim</a>
        <a href="../logout.php">Çıkış</a>
    </div>
</div>

<div class="konteyner2">
<?php
$sql = "SELECT e.etkinlik_id, e.etkinlik_adi, e.etkinlik_aciklamasi, e.etkinlik_tarihi, e.yeni_tarih, e.durum 
        FROM etkinlikler e 
        JOIN etkinlik_bolum eb on e.etkinlik_id = eb.etkinlik_id 
        WHERE eb.bolum_id='$bolumID' and e.durum != 'iptal'";
$sonuc = mysqli_query($conn, $sql);

if (mysqli_num_rows($sonuc) > 0) {
    echo "<table>";
    echo "<tr><th>ID</th><th>Etkinlik</th><th>Açıklama</th><th>Tarih</th><th>Aktif</th><th>Detay</th></tr>";

    while ($row = mysqli_fetch_assoc($sonuc)) {
        $etkinlikID = $row["etkinlik_id"];
        $etkinlikTarihi = $row["etkinlik_tarihi"];
        $yeniTarih = $row["yeni_tarih"];
        $bugun = date("Y-m-d");

        echo "<tr>";
        echo "<td>" . htmlspecialchars($etkinlikID) . "</td>";
        echo "<td>" . htmlspecialchars($row["etkinlik_adi"]) . "</td>";
        echo "<td>" . htmlspecialchars($row["etkinlik_aciklamasi"]) . "</td>";
        echo "<td>" . htmlspecialchars(is_null($yeniTarih) ? $etkinlikTarihi : $yeniTarih) . "</td>";
        echo "<td>" . htmlspecialchars($row["durum"]) . "</td>";

        // Tarih kontrolü
        $etkinlikZamani = is_null($yeniTarih) ? $etkinlikTarihi : $yeniTarih;
        if (strtotime($etkinlikZamani) < strtotime($bugun)) {
            echo "<td><span style='color:gray;'>Tarihi Geçti</span></td>";
        } else {
            $sql2 = "SELECT * FROM katilimlar WHERE etkinlik_id='$etkinlikID' AND ogrenci_id = '$id'";
            $sonuc2 = mysqli_query($conn, $sql2);

            if (mysqli_num_rows($sonuc2) > 0) {
                echo "<td>
                    <form method='post'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($etkinlikID) . "'>
                        <input type='submit' name='dugme' value='Etkinlikten Çık' 
                         style='background-color: #f7a8a8; color: #6b0000; border: none; cursor: pointer;' >
                    </form>
                  </td>";
            } else {
                echo "<td>
                    <form method='post'>
                        <input type='hidden' name='id' value='" . htmlspecialchars($etkinlikID) . "'>
                        <input type='submit' name='dugme' value='Etkinliğe Katıl'
                        style='background-color: #a8f7b0; color: #1b4d00; border: none; cursor: pointer;' >
                    </form>
                  </td>";
            }
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<div class='mesaj'>Kayıt bulunamadı.</div>";
}
?>
</div>
</body>
</html>
