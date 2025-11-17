<?php
session_start();
require_once "../db.php"; // db.php ile PDO bağlantısı

// ❌ OTOMATİK YÖNLENDİRME KALDIRILDI
// Artık login sayfası açılır açılmaz panel'e atmayacak

// Form gönderilmiş mi?
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Lütfen tüm alanları doldurun!";
    } else {

        // Sadece editör rolüne bakıyoruz
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND role = 'editor'");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {

            // Giriş başarılı
            $_SESSION["editor_user"] = $user['username'];
            $_SESSION["editor_id"] = $user['id'];
            $_SESSION["role"] = $user['role'];

            header("Location: editor_panel.php");
            exit();
        } else {
            $error = "Kullanıcı bulunamadı veya şifre yanlış!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Editör Girişi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body { background-color: #0a192f; display: flex; justify-content: center; align-items: center; height: 100vh; margin:0; }
.card { width: 400px; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); background-color: #ffffff; }
.card h3 { color: #0a192f; }
</style>
</head>
<body>
<div class="card">
    <h3 class="text-center mb-4">Editör Girişi</h3>

    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="POST">
        <div class="mb-3">
            <label>Kullanıcı Adı</label>
            <input type="text" class="form-control" name="username" placeholder="Kullanıcı Adı" required>
        </div>
        <div class="mb-3">
            <label>Şifre</label>
            <input type="password" class="form-control" name="password" placeholder="Şifre" required>
        </div>
        <button type="submit" class="btn btn-warning w-100">Giriş Yap</button>
    </form>
</div>
</body>
</html>
