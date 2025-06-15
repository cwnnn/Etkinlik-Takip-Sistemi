<?php if (isset($_GET['hata'])): ?>
  <div class="mesaj"><?php echo htmlspecialchars($_GET['hata']); ?></div>
<?php elseif (isset($_GET['basari'])): ?>
  <div class="mesaj" style="background-color: green;"><?php echo htmlspecialchars($_GET['basari']); ?></div>
<?php endif; ?>
<?php

  
session_start();
include '../baglanti.php'; // $conn bağlantısı burada
require_once "../session_kontrol.php";
if (isset($_SESSION['kullanici_id'])) {
    $id = $_SESSION['kullanici_id'];
} else {
    // Oturum yoksa giriş sayfasına yönlendir
    header("Location: ../giris.php");
    exit;
}

$stmt = $conn->prepare("SELECT ogrenci_adi, ogrenci_soyadi, ogrenci_eposta, bolum_id,sinif ,ogrenci_sifre FROM ogrenciler WHERE ogrenci_id = ? ");
$stmt->execute([$id]);
$ogrenci = $stmt->get_result()->fetch_assoc();


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
        <form class="formGiris" method="post" action="profilimGuncelle.php">
            <h3>Profil Bilgilerim</h3>
            <input type="text" name="name" placeholder="Adınız" required value="<?php echo htmlspecialchars($ogrenci['ogrenci_adi'] ?? ''); ?>" />
            <input type="text" name="surname" placeholder="Soyadınız" required value="<?php echo htmlspecialchars($ogrenci['ogrenci_soyadi'] ?? ''); ?>" />
            <input type="text" name="mail" placeholder="E-postanız" required value="<?php echo htmlspecialchars($ogrenci['ogrenci_eposta'] ?? ''); ?>" />

    <select class="dropdawn1" name="bolumler" id="bolum">
        <option value="">-- Bölüm Seçiniz --</option>
        <?php
        require_once '../baglanti.php';
        $bolumler = mysqli_query($conn, "SELECT * FROM bolumler");
        while ($bolum = mysqli_fetch_assoc($bolumler)) {
            $selected = (($ogrenci['bolum_id'] ?? '') == $bolum['bolum_id']) ? 'selected' : '';
            echo "<option value='" . $bolum['bolum_id'] . "' $selected>" . $bolum['bolum_adi'] . "</option>";
        }
        ?>
    </select>

            <select class="dropdawn1" name="siniflar" id="sinif">
                <option value="0">-- Sınıf Seçiniz --</option>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <option value="<?php echo $i; ?>" <?php if (($ogrenci['sinif'] ?? '') == $i) echo 'selected'; ?>>
                        <?php echo $i; ?>.Sınıf
                    </option>
                <?php endfor; ?>
            </select> 
            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Şifreniz"
                    required
                    value="<?php echo htmlspecialchars($ogrenci['ogrenci_sifre'] ?? ''); ?>"
                />
                <img
                    src="../resimler/close-eye.png"
                    alt="Şifre göster/gizle"
                    class="toggle-password"
                    id="togglePassword"
                    onclick="sifreGosterGizle()"
                />
            </div>
            <button type="submit">Güncelle</button>
        </form>
    </div>

    <script>
    function sifreGosterGizle() {
      const sifreInput = document.getElementById("password");
      const toggleIcon = document.getElementById("togglePassword");

      if (sifreInput.type === "password") {
        sifreInput.type = "text";
        toggleIcon.src = "../resimler/open-eye.png";
      } else {
        sifreInput.type = "password";
        toggleIcon.src = "../resimler/close-eye.png";
      }
    }
    </script>
</body>
</html>