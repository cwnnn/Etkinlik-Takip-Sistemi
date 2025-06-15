<?php if (isset($_GET['hata'])): ?>
  <div class="mesaj"><?php echo htmlspecialchars($_GET['hata']); ?></div>
<?php elseif (isset($_GET['basari'])): ?>
  <div class="mesaj" style="background-color: green;"><?php echo htmlspecialchars($_GET['basari']); ?></div>
<?php endif; 

require_once "../session_kontrol.php";
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
<a href="profilim.php">Profilim</a>
<a href="katilim.php">Katılımlar</a>
<a href="AdminAnaSayfa.php">Etkinlik Oluştur</a>
<a href="../logout.php">Çıkış</a>
    
  </div>
</div>
<div>
    <form class="formGiris" method="POST" action="ogretmenEklemeISlemi.php" onsubmit="return formKontrol();">
        <h2 style="padding: 10px;">Kullanıcı Ekle</h2>
        <input type="text" name="username" placeholder="Kullanıcı Adınız" required />
        <select  class="dropdawn1" name="roller" id="rol">
            <option value="0">-- Rol Seçiniz --</option>
            <option value="admin">Admin</option>
            <option value="ogretmen">Öğretmen</option>
        </select>
    <div class="password-wrapper">
  <input
    type="password"
    id="password"
    name="password"
    placeholder="Şifreniz"
    required
  />
  <img
    src="../resimler/close-eye.png"
    alt="Şifre göster/gizle"
    class="toggle-password"
    id="togglePassword"
    onclick="sifreGosterGizle('password', 'togglePassword')"
  />
</div>  
        <button type="submit">Öğretmen Ekle</button>
    </form>
 </div>

<script>
  function sifreGosterGizle(inputId, iconId) {
    const sifreInput = document.getElementById(inputId);
    const toggleIcon = document.getElementById(iconId);

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


  if (rol    === '0' ) {
    alert('Lütfen bir rol seçiniz.');
    return false; // form gönderilmesin
  }


}
</script>
</body>
</html>