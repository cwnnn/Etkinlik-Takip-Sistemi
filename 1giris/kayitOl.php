
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
    <title>Document</title>
     <link rel="stylesheet" href="../cssDosyalari/navbar.css" />
      <link rel="stylesheet" href="../cssDosyalari/divler.css">
      <link rel="stylesheet" href="../cssDosyalari/formlar.css">
</head>
<body>
     <div class="navbar">
    <div class="geritusu" onclick="history.back()" aria-label="Geri dön">
      <img class="back-icon" src="../resimler/geri_iconu.png" alt="Geri" />
    </div>
    <a style="font-size: 22px;">Kayıt Ol</a>
  </div>

  <form class="formGiris" method="POST" action="kayitOlIcerik.php" onsubmit="return formKontrol();">
    
      <h2 style="padding: 10px;">Öğrenci Kayıt Ol</h2>
      <input type="text" name="name" placeholder="Adınız" required />
      <input type="text" name="surname" placeholder="Soyadınız" required />
      <input type="text" name="mail" placeholder="E-postanız" required />

      <select class="dropdawn1" name="bolumler" id="bolum"> <!-- cssi divler.css nin içinde -->
        <option value="0">-- Bölüm Seçiniz --</option>
        <?php
        require_once '../baglanti.php';
        $bolumler = mysqli_query($conn, "SELECT * FROM bolumler");

    while ($bolum = mysqli_fetch_assoc($bolumler)) {
        echo "<option value='" . $bolum['bolum_id'] . "'>" . $bolum['bolum_adi'] . "</option>";
    }
        
        ?>
      </select>

      <select class="dropdawn1" name="siniflar" id="sinif"> <!-- cssi divler.css nin içinde -->
        <option value="0">-- SınıfS Seçiniz --</option>
        <option value="1">1.Sınıf</option>
        <option value="2">2.Sınıf</option>
        <option value="3">3.Sınıf</option>
        <option value="4">4.Sınıf</option>
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

<div class="password-wrapper">
  <input
    type="password"
    id="password2"
    name="password2"
    placeholder="Tekrar Şifreniz"
    required
  />
  <img
    src="../resimler/close-eye.png"
    alt="Şifre göster/gizle"
    class="toggle-password"
    id="togglePassword2"
    onclick="sifreGosterGizle('password2', 'togglePassword2')"
  />
</div>

<button type="submit">Kayıt Ol</button>

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
  const bolum = document.getElementById('bolum').value;
  const sinif = document.getElementById('sinif').value;

  if (bolum === '0' ) {
    alert('Lütfen bir bölüm seçiniz.');
    return false; // form gönderilmesin
  }

  if (sinif === '0') {
    alert('Lütfen bir sınıf seçiniz.');
    return false; // form gönderilmesin
  }

  return true; // form gönderilebilir
}
</script>
</body>
</html>