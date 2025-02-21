<?php
// login.php
session_start();

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $credentials = file("bilgi.txt", FILE_IGNORE_NEW_LINES);

    foreach ($credentials as $line) {
        list($storedUsername, $storedPassword, $role) = explode(",", $line);
        if ($username === $storedUsername && $password === $storedPassword) {
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role === 'sekreter') {
                header("Location: sekreter_paneli.php");
                exit;
            } elseif ($role === 'doktor') {
                header("Location: doktor_paneli.php");
                exit;
            }
        }
    }

    $error = "Kullanıcı adı veya şifre hatalı.";
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3 class="text-center">Giriş Yap</h3>
                        <?php if (isset($error)) echo "<p class='text-danger'>$error</p>"; ?>
                        <form method="post" action="" class="mt-3">
                            <div class="mb-3">
                                <label class="form-label">Kullanıcı Adı:</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Şifre:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Giriş Yap</button>
                        </form>
                        <div class="text-center mt-3">
                            <a href="sifre_unuttum.php" class="link-secondary">Şifremi Unuttum</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
