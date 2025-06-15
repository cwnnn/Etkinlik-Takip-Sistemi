<?php
session_start();
require_once '../baglanti.php';
require_once "../session_kontrol.php";
// Kullanıcı oturum kontrolü
if (!isset($_SESSION['kullanici_id'])) {
    header("Location: ../giris.php");
    exit;
}

// Etkinlik ID gelmezse geri gönder
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['etkinlik_id'])) {
    header("Location: katilim.php");
    exit;
}

$etkinlik_id = intval($_POST['etkinlik_id']);
$kullanici_id = $_SESSION['kullanici_id'];

// Etkinlik verisini sadece ilgili kullanıcıya aitse al
$stmt = $conn->prepare("SELECT e.*, eb.bolum_id FROM etkinlikler e JOIN etkinlik_bolum eb ON e.etkinlik_id = eb.etkinlik_id WHERE e.etkinlik_id = ? AND e.olusturan_id = ?");
$stmt->bind_param("ii", $etkinlik_id, $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Etkinlik bulunamadı veya bu etkinliği güncelleyemezsiniz.";
    exit;
}

$etkinlik = $result->fetch_assoc();
$stmt->close();

// datetime alanını parçalıyoruz
$datetime = $etkinlik['etkinlik_tarihi'];
$tarih = date('Y-m-d', strtotime($datetime));
$saat = date('H:i', strtotime($datetime));
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="../cssDosyalari/navbar.css" />
    <link rel="stylesheet" href="../cssDosyalari/divler.css" />
    <link rel="stylesheet" href="../cssDosyalari/formlar.css" />
    <link rel="stylesheet" href="../cssDosyalari/tablelar.css" />
    <title>Etkinlik Güncelle</title>
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
    $role = $_SESSION['rol'] ?? null;
    if ($role === 'admin') {
        echo '<a href="ogretmenEkle.php">Kullanıcı Ekle</a>';
        echo '<a href="loglar.php">Logları</a>';
    }
    ?>
    <a href="profilim.php">Profilim</a>
    <a href="katilim.php">Katılım</a>
    <a href="AdminAnaSayfa.php">Etkinlik Oluştur</a>
    <a href="../logout.php">Çıkış</a>
  </div>
</div>

<div class="konteyner2">
    <form class="formGiris" method="post" action="etkinlikGuncelleSonuc.php" onsubmit="return formKontrol();">
        <h3>Etkinlik Bilgileri</h3>
        <input type="hidden" name="etkinlik_id" value="<?php echo htmlspecialchars($etkinlik['etkinlik_id']); ?>" />
        
        <input type="text" name="etkinlik_adi" placeholder="Etkinlik Adı" required value="<?php echo htmlspecialchars($etkinlik['etkinlik_adi']); ?>" />
        
        <input type="text" name="etkinlik_aciklama" placeholder="Etkinlik Açıklaması" required value="<?php echo htmlspecialchars($etkinlik['etkinlik_aciklamasi']); ?>" />
        
        <input type="date" name="etkinlik_tarihi" placeholder="Etkinlik Tarihi" required 
            value="<?php echo htmlspecialchars($tarih); ?>" 
            min="<?php echo date('Y-m-d'); ?>" />
        
        <input type="time" name="etkinlik_saati" placeholder="Etkinlik Saati" required value="<?php echo htmlspecialchars($saat); ?>" />
        
        <select class="dropdawn1" name="durum" required>
            <option value="aktif" <?php echo ($etkinlik['durum'] === 'aktif') ? 'selected' : ''; ?>>Aktif</option>
            <option value="ertelendi" <?php echo ($etkinlik['durum'] === 'ertelendi') ? 'selected' : ''; ?>>Ertelendi</option>
            <option value="iptal" <?php echo ($etkinlik['durum'] === 'iptal') ? 'selected' : ''; ?>>İptal</option>
        </select>
        
        <input type="text" name="aciklama" placeholder="Açıklamanız" required />

        <select class="dropdawn1" name="Bolumler" id="Bolum">
            <option value="0">-- Bölüm Seçiniz --</option>
            <?php
            // Bölümleri çekiyoruz
            $stmt = $conn->prepare("SELECT bolum_id, bolum_adi FROM Bolumler");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
            $selected = ($row['bolum_id'] == $etkinlik['bolum_id']) ? "selected" : "";
            echo "<option value='" . htmlspecialchars($row['bolum_id']) . "' $selected>" . htmlspecialchars($row['bolum_adi']) . "</option>";
            }
            ?>
        </select>



        <button type="submit">Güncelle</button>
    </form>
</div>
</body>
</html>
