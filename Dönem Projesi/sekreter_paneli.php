<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'sekreter') {
    header("Location: login.php");
    exit;
}

if (isset($_SESSION['timeout']) && time() - $_SESSION['timeout'] > 1800) {
    session_destroy();
    header("Location: login.php");
    exit;
}
$_SESSION['timeout'] = time();

if (isset($_POST['hasta_kaydet'])) {
    $hasta_ad = $_POST['hasta_ad'];
    $hasta_soyad = $_POST['hasta_soyad'];
    $hasta_tc = $_POST['hasta_tc'];
    $randevu_tarih = $_POST['randevu_tarih'];
    $randevu_saat = $_POST['randevu_saat'];

    $db = new mysqli("localhost", "root", "", "randevu_sistemi");
    if ($db->connect_error) {
        die("Bağlantı hatası: " . $db->connect_error);
    }

    $stmt = $db->prepare("INSERT INTO hastalar (ad, soyad, tc, randevu_tarih, randevu_saat) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $hasta_ad, $hasta_soyad, $hasta_tc, $randevu_tarih, $randevu_saat);
    $stmt->execute();
    $stmt->close();
    $db->close();

    $message = "Hasta başarıyla kaydedildi.";
}

if (isset($_POST['randevu_iptal'])) {
    $randevu_id = $_POST['randevu_id'];

    $db = new mysqli("localhost", "root", "", "randevu_sistemi");
    if ($db->connect_error) {
        die("Bağlantı hatası: " . $db->connect_error);
    }

    $stmt = $db->prepare("DELETE FROM hastalar WHERE id = ?");
    $stmt->bind_param("i", $randevu_id);
    $stmt->execute();
    $stmt->close();
    $db->close();

    $message = "Randevu başarıyla iptal edildi.";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sekreter Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h2 class="text-center">Sekreter Paneli</h2>
        <?php if (isset($message)) echo "<p class='text-success'>$message</p>"; ?>

        <div class="card shadow my-3">
            <div class="card-body">
                <h5 class="card-title">Hasta Kaydet</h5>
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Ad:</label>
                        <input type="text" name="hasta_ad" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Soyad:</label>
                        <input type="text" name="hasta_soyad" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">TC Kimlik No:</label>
                        <input type="text" name="hasta_tc" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Randevu Tarihi:</label>
                        <input type="date" name="randevu_tarih" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Randevu Saati:</label>
                        <input type="time" name="randevu_saat" class="form-control" required>
                    </div>
                    <button type="submit" name="hasta_kaydet" class="btn btn-primary w-100">Kaydet</button>
                </form>
            </div>
        </div>

        <div class="card shadow my-3">
            <div class="card-body">
                <h5 class="card-title">Randevu İptal</h5>
                <form method="post" action="">
                    <div class="mb-3">
                        <label class="form-label">Randevu ID:</label>
                        <input type="number" name="randevu_id" class="form-control" required>
                    </div>
                    <button type="submit" name="randevu_iptal" class="btn btn-danger w-100">Randevu İptal</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
