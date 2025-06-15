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
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['bildirim_id'])) {
    $bildirim_id = intval($_POST['bildirim_id']);
    $stmt = $conn->prepare("UPDATE bildirimler SET okundu_mu = 1 WHERE bildirim_id = ? AND ogrenci_id = ?");
    $stmt->bind_param("ii", $bildirim_id, $id);
    $stmt->execute();
    // Sayfayı yenile
    header("Location: bildirimler.php");
    exit;
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
    <table >
      
    <?php
    echo "<th>Mesaj</th><th>Bildirim Tarihi</th><th>Okundu</th></tr>";
    $sql = "SELECT * FROM bildirimler WHERE ogrenci_id ='$id'";
    $sonuc = mysqli_query($conn, $sql);

    if (mysqli_num_rows($sonuc) > 0) {
        while ($row = mysqli_fetch_assoc($sonuc)) {


            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["mesaj"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["olusturma_tarihi"]) . "</td>";
            if ($row["okundu_mu"] == 0) {
                echo "<td>
                <form method='post'>
                    <input type='hidden' name='bildirim_id' value='" . htmlspecialchars($row["bildirim_id"]) . "'>
                    <input type='submit' name='dugme' value='Okunmadı' 
                     style='background-color: #f7a8a8; color: #6b0000; border: none; cursor: pointer;' >
                </form>
                </td>";
            } else {
                echo "<td><a>Okundu</a></td>";
            }
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='2'>Bildirim bulunamadı.</td></tr>";
    }
    ?>
    </table>
    </div>
</body>
</html>