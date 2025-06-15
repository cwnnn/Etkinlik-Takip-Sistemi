<?php if (isset($_GET['hata'])): 
  require_once "../session_kontrol.php";?>
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
session_start();
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
    <form class="formGiris" style="top: 20%;" method="POST"  onsubmit="return formKontrol();">
        <h2 style="padding: 10px;">Kullanıcı Seçiniz</h2>

        <select class="dropdawn1" name="Ogrenciler" id="rol">
            <option value="0">-- Öğrenci Seçiniz --</option>
           <?php
        require_once '../baglanti.php';
        $bolumler = mysqli_query($conn, "SELECT ogrenci_id, ogrenci_adi, ogrenci_soyadi FROM ogrenciler;");

    while ($bolum = mysqli_fetch_assoc($bolumler)) {
        echo "<option value='" . $bolum['ogrenci_id'] . "'>" . $bolum['ogrenci_adi'] . " " . $bolum['ogrenci_soyadi'] . "</option>";
    }

        ?>
        </select>
        <button type="submit">Hareketlerine bak</button>
    </form>

    <div class="konteyner2" style="padding-top: 230px;">
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Ogrenciler']) && $_POST['Ogrenciler'] !== '0') {
    require_once '../baglanti.php';
    $ogrenci_id = intval($_POST['Ogrenciler']);
    $query = "SELECT * FROM logs WHERE ogrenci_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $ogrenci_id);
    $stmt->execute();
    $result = $stmt->get_result();

     
    echo '<h3 style="margin-top:20px;">Seçilen Öğrencinin Logları</h3>';
    if ($result->num_rows > 0) {
        echo '<table ><tr><th>ID</th><th>Ogrenci ID</th><th>İşlem</th><th>Tarih</th></tr>';
        while ($row = $result->fetch_assoc()) {
            echo '<tr>';    
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['ogrenci_id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['hareket']) . '</td>';
            echo '<td>' . htmlspecialchars($row['hareket_date']) . '</td>';
            echo '</tr>';
        }   
        echo '</table>';
    } else {
        echo '<div class="mesaj">Bu öğrenciye ait log bulunamadı.</div>';
    }
    $stmt->close();
}
?>
 </div>
</div>

<script>


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