<?php
if (isset($_POST['reset_password'])) {
    $email = $_POST['email'];

    $users = file("bilgi.txt", FILE_IGNORE_NEW_LINES);
    $userFound = false;

    foreach ($users as $user) {
        list($storedEmail, $storedPassword, $role) = explode(",", $user);
        if ($email === $storedEmail) {
            // Şifre sıfırlama e-postası simülasyonu
            $message = "Şifreniz: $storedPassword";
            mail($email, "Şifre Sıfırlama", $message); // Gerçek bir mail fonksiyonu yerine test ortamında basit bir mesaj dönecek.
            $success = "Şifre sıfırlama bilgisi e-postanıza gönderildi.";
            $userFound = true;
            break;
        }
    }

    if (!$userFound) {
        $error = "E-posta sistemde bulunamadı.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifremi Unuttum</title>
</head>
<body>
    <h2>Şifremi Unuttum</h2>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post" action="">
        <label>E-posta Adresi:</label>
        <input type="email" name="email" required>
        <br>
        <button type="submit" name="reset_password">Şifremi Sıfırla</button>
    </form>
    <a href="login.php">Giriş Sayfasına Dön</a>
</body>
</html>
