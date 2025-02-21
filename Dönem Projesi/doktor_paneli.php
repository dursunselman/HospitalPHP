<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'doktor') {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['timeout']) && time() - $_SESSION['timeout'] > 1800) {
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['timeout'] = time();

if (isset($_POST['sekreter_ekle'])) {
    $sekreter_ad = $_POST['sekreter_ad'];
    $sekreter_sifre = $_POST['sekreter_sifre'];

    $file = fopen("bilgi.txt", "a");
    fwrite($file, "$sekreter_ad,$sekreter_sifre,sekreter\n");
    fclose($file);

    $message = "Sekreter başarıyla eklendi.";
}

if (isset($_POST['sekreter_sil'])) {
    $sekreter_ad = $_POST['sekreter_ad'];

    $lines = file("bilgi.txt", FILE_IGNORE_NEW_LINES);
    $newLines = array_filter($lines, function ($line) use ($sekreter_ad) {
        return strpos($line, "$sekreter_ad,sekreter") === false;
    });

    file_put_contents("bilgi.txt", implode("\n", $newLines));
    $message = "Sekreter başarıyla silindi.";
}

if (isset($_POST['muayene_kaydet'])) {
    $hasta_id = $_POST['hasta_id'];
    $sonuc = $_POST['muayene_sonuc'];
    $ilaclar = $_POST['ilaclar'];

    $db = new mysqli("localhost", "root", "", "randevu_sistemi");
    if ($db->connect_error) {
        die("Bağlantı hatası: " . $db->connect_error);
    }

    $stmt = $db->prepare("UPDATE hastalar SET muayene_sonuc = ?, ilaclar = ? WHERE id = ?");
    $stmt->bind_param("ssi", $sonuc, $ilaclar, $hasta_id);
    $stmt->execute();
    $stmt->close();
    $db->close();

    $message = "Muayene bilgisi başarıyla kaydedildi.";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Doktor Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Doktor Paneli</h2>
        <?php if (isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

        <!-- Sekreter Yönetimi -->
        <div class="card shadow my-3">
            <div class="card-body">
                <h5 class="card-title">Sekreter Yönetimi</h5>
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Sekreter Adı:</label>
                        <input type="text" name="sekreter_ad" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Şifre:</label>
                        <input type="password" name="sekreter_sifre" class="form-control" required>
                    </div>
                    <button type="submit" name="sekreter_ekle" class="btn btn-success w-100">Sekreter Ekle</button>
                </form>
                <hr>
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Sekreter Adı (Silinecek):</label>
                        <input type="text" name="sekreter_ad" class="form-control" required>
                    </div>
                    <button type="submit" name="sekreter_sil" class="btn btn-danger w-100">Sekreter Sil</button>
                </form>
            </div>
        </div>

        <!-- Hasta Muayene Yönetimi -->
        <div class="card shadow my-3">
            <div class="card-body">
                <h5 class="card-title">Hasta Muayene Yönetimi</h5>
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Hasta ID:</label>
                        <input type="number" name="hasta_id" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Muayene Sonucu:</label>
                        <textarea name="muayene_sonuc" class="form-control" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Verilecek İlaçlar:</label>
                        <textarea name="ilaclar" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="muayene_kaydet" class="btn btn-primary w-100">Muayene Kaydet</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
