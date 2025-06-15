<?php if (isset($_GET['hata'])): ?>
  <div class="mesaj"><?php echo htmlspecialchars($_GET['hata']); ?></div>
<?php elseif (isset($_GET['basari'])): ?>
  <div class="mesaj" style="background-color: green;"><?php echo htmlspecialchars($_GET['basari']); ?></div>
<?php endif; ?>


<?php

  require_once "../session_kontrol.php";
session_start();
include '../baglanti.php'; // $conn bağlantısı burada

if (isset($_SESSION['kullanici_id'])) {
    $id = $_SESSION['kullanici_id'];
} else {
    // Oturum yoksa giriş sayfasına yönlendir
    header("Location: ../giris.php");
    exit;
}

$stmt = $conn->prepare("SELECT kullaniciadi, sifre FROM kullanicilar WHERE kullanici_id  = ? ");
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
$role = isset($_SESSION['rol']) ? $_SESSION['rol'] : null;
if ($role === 'admin') {
    echo '<a href="ogretmenEkle.php">Kullanıcı Ekle</a>';
    echo '<a href="loglar.php">Loglar</a>';
}
?>

<a href="profil.php">Profilim</a>
<a href="katilim.php">Katılımlar</a>
<a href="AdminAnaSayfa.php">Etkinlik Oluştur</a>
<a href="../logout.php">Çıkış</a>
    
  </div>
</div>


  <div class="konteyner2">
        <form class="formGiris" method="post" action="profilimGuncelleOgr.php" onsubmit="return formKontrol();">
            <h3>Profil Bilgilerim</h3>

            <input type="text" name="username" placeholder="Kullanıcı Adınız" required value="<?php echo htmlspecialchars($ogrenci['kullaniciadi'] ?? ''); ?>" />
    

            <div class="password-wrapper">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Şifreniz"
                    required
                    value="<?php echo htmlspecialchars($ogrenci['sifre'] ?? ''); ?>"
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

    function formKontrol() {
  const rol = document.getElementById('rol').value;




}
    </script>
</body>
</html>