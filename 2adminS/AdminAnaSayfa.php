<?php
session_start();
require_once '../baglanti.php';
require_once "../session_kontrol.php";
// Kullanıcı oturum kontrolü
?>
<?php
// Sayfanın üst kısmına ekleyebilirsin
if (isset($_GET['success'])) {
    // Başarı mesajı varsa göster
    echo '<div class="mesaj style="color: green;">' . htmlspecialchars($_GET['success']) . '</div>';
} elseif (isset($_GET['error'])) {
    // Hata mesajı varsa göster
    echo '<div class="mesaj">' . htmlspecialchars($_GET['error']) . '</div>';
}
?>

<?php if (isset($_GET['hata'])): ?>
  <div class="mesaj"><?php echo htmlspecialchars($_GET['hata']); ?></div>
<?php elseif (isset($_GET['basari'])): ?>
  <div class="mesaj" style="background-color: green;"><?php echo htmlspecialchars($_GET['basari']); ?></div>
<?php endif; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../cssDosyalari/navbar.css" />
    <link rel="stylesheet" href="../cssDosyalari/divler.css">
     <link rel="stylesheet" href="../cssDosyalari/formlar.css">
     <link rel="stylesheet" href="../cssDosyalari/tablelar.css">
    <title>Document</title>
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
$role = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
if ($role === 'admin') {
    echo '<a href="ogretmenEkle.php">Kullanıcı Ekle</a>';
    echo '<a href="loglar.php">Logları Gör</a>';
}
?>
<a href="profilim.php">Profilim</a>
<a href="katilim.php">Katılım</a>
<a href="AdminAnaSayfa.php">Etkinlik Oluştur</a>
<a href="../logout.php">Çıkış</a>
    
  </div>
</div>
<div class="konteyner2">
    <form class="formGiris" method="post" action="etkinlikEklemeSonuc.php">
        <h3>Etkinlik Bilgileri</h3>
        <input type="hidden" name="etkinlik_id" value="<?php echo htmlspecialchars($etkinlik['etkinlik_id']); ?>" />
        
        <input type="text" name="etkinlik_adi" placeholder="Etkinlik Adı" required/>

        <input type="text" name="etkinlik_aciklama" placeholder="Etkinlik Açıklaması" required/>
        
        <input type="date" name="etkinlik_tarihi" placeholder="Etkinlik Tarihi" required min="<?php echo date('Y-m-d'); ?>"/>
        
        <input type="time" name="etkinlik_saati" placeholder="Etkinlik Saati" required/>
        
        <select class="dropdawn1" name="durum" required>
            <option value="aktif">Aktif</option>
            <option value="ertelendi">Ertelendi</option>
            <option value="iptal">İptal</option>
        </select>
        

        , <select class="dropdawn1" name="Bolumler" id="Bolum">

            <?php
            // Bölümleri çekiyoruz
            $stmt = $conn->prepare("SELECT bolum_id, bolum_adi FROM Bolumler");
            $stmt->execute();
            $result = $stmt->get_result();

            while ($row = $result->fetch_assoc()) {
            //$selected = ($row['bolum_id'] == $etkinlik['bolum_id']) ? "selected" : "";
            echo "<option value='" . htmlspecialchars($row['bolum_id']) . "' $selected>" . htmlspecialchars($row['bolum_adi']) . "</option>";
            }
            ?>

        </select>

        <button type="submit">Etkinlik Ekle</button>

    </form>
</div>

</body>
</html>