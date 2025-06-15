<?php
session_start();
$mesaj = isset($_GET['error']) ? $_GET['error'] : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Öğretmen Girişi</title>
  <link rel="stylesheet" href="../cssDosyalari/navbar.css" />
   <link rel="stylesheet" href="../cssDosyalari/divler.css">
   <link rel="stylesheet" href="../cssDosyalari/formlar.css">
  
  <style>
   
    

  </style>
</head>
<body>

  <div class="navbar">
    <div class="geritusu" onclick="history.back()" aria-label="Geri dön">
      <img class="back-icon" src="../resimler/geri_iconu.png" alt="Geri" />
    </div>
    <a style="font-size: 22px;">Etkinlik Takip Sistemi</a>
  </div>

  <div>
    
    <form class="formGiris" method="POST" action="girisOg.php">
      <h2 style="padding: 10px;">Öğretmen Girişi</h2>
      <input type="text" name="username" placeholder="Kullanıcı Adı" required />

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
          onclick="sifreGosterGizle()"
        />
      </div>

      <button type="submit">Giriş Yap</button>
    </form>
  </div>

 <?php if (!empty($mesaj)): ?>
  <div class="mesaj">
    <?php echo $mesaj; ?>
  </div>
<?php endif; ?>

  <script>
    // Şifre göster/gizle fonksiyonu
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
