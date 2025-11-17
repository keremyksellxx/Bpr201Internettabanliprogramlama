<?php
require_once "../db.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        $error = "Lütfen tüm alanları doldurun.";
    } else {
        // Kullanıcıyı ve rolü kontrol et (sadece admin)
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND role = 'admin'");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin["password"])) {
            // Giriş başarılı, session atama
            $_SESSION["admin_user"] = $admin["username"];
            $_SESSION["admin_fullname"] = $admin["fullname"];
            $_SESSION["admin_id"] = $admin["id"];
            header("Location: admin_panel.php"); // Admin paneline yönlendir
            exit();
        } else {
            $error = "Kullanıcı adı veya şifre yanlış!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Girişi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background-color: #0a192f; display: flex; justify-content: center; align-items: center; height: 100vh; }
  .card { width: 400px; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); background-color: #ffffff; }
  .card h3 { color: #0a192f; }
</style>
</head>
<body>
<div class="card">
  <h3 class="text-center mb-4">Admin Girişi</h3>
  <?php if(!empty($error)) echo '<div class="alert alert-danger">'.$error.'</div>'; ?>
  <form method="POST" action="">
    <div class="mb-3">
      <label>Kullanıcı Adı</label>
      <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required>
    </div>
    <div class="mb-3">
      <label>Şifre</label>
      <input type="password" name="password" class="form-control" placeholder="Şifre" required>
    </div>
    <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
  </form>
</div>
</body>
</html>
