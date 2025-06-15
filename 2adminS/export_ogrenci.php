<?php
require_once '../baglanti.php';

// etkinlik_id alımı
$etkinlik_id = 0;
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['etkinlik_id'])) {
    $etkinlik_id = (int) htmlspecialchars(trim($_POST['etkinlik_id']));
} elseif (isset($_GET['etkinlik_id'])) {
    $etkinlik_id = (int) $_GET['etkinlik_id'];
}

$sql = "SELECT o.ogrenci_adi, o.ogrenci_soyadi, o.ogrenci_eposta  
        FROM katilimlar k
        JOIN ogrenciler o ON k.ogrenci_id = o.ogrenci_id
        WHERE k.etkinlik_id = ?
        ORDER BY ogrenci_adi";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $etkinlik_id);
$stmt->execute();
$result = $stmt->get_result();

header("Content-Type: application/vnd.ms-excel; charset=UTF-8");
header("Content-Disposition: attachment; filename=etkinlik_{$etkinlik_id}_ogrenciler.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Excel'de Türkçe karakter için UTF-8 BOM ekle
echo "\xEF\xBB\xBF";

echo "<table border='1'>";
echo "<tr><th>Ad</th><th>Soyad</th><th>Eposta</th></tr>";

while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['ogrenci_adi']) . "</td>";
    echo "<td>" . htmlspecialchars($row['ogrenci_soyadi']) . "</td>";
    echo "<td>" . htmlspecialchars($row['ogrenci_eposta']) . "</td>";
    echo "</tr>";
}

echo "</table>";
exit;
?>
