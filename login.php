<!DOCTYPE html>
<html lang="tr">
<head>
<?php
/**
 * Kullanıcı Giriş Sayfası
 * Session zaten includes/security.php tarafından başlatılıyor
 */

require_once "db.php";
// Session kontrolü artık gerekli değil - security.php otomatik hallediyor

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {
        die("Lütfen tüm alanları doldurun.");
    }

    $query = $db->prepare("SELECT * FROM users WHERE username = ?");
    $query->execute([$username]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (password_verify($password, $user["password"])) {

            $_SESSION["user_id"]       = $user["id"];
            $_SESSION["username"]      = $user["username"];
            $_SESSION["user_fullname"] = $user["fullname"];

            header("Location: index.php");
            exit();

        } else {
            $login_error = "Şifre yanlış!";
        }
    } else {
        $login_error = "Kullanıcı bulunamadı!";
    }
}
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Giriş Yap | Haber Portalı</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root{
      --bg-dark:#0a192f;
      --card-dark:#1b263b;
      --accent:#00b4d8;
      --input-border:#0ea5bf;
    }

    body {
      background: var(--bg-dark);
      font-family: 'Segoe UI', sans-serif;
      color: #dce1eb;
      height: 100vh;
      overflow:hidden;
    }

    /* REGISTER İLE AYNI ANİMASYON */
    .login-box {
      background: var(--card-dark);
      border-radius: 16px;
      padding: 40px;
      box-shadow: 0 8px 30px rgba(0,0,0,0.45);
      border: 1px solid rgba(255,255,255,0.05);
      animation: slideDown 0.8s ease forwards;
      opacity: 0;
      transform: translateY(-40px);
    }


    / body {
        background-color: #0a192f;
        font-family: 'Segoe UI', sans-serif;
    }

    ::placeholder {
        color: #eee !important;
        opacity: 1 !important;
    }

    .register-box {
        background-color: #112240;
        border-radius: 16px;
        padding: 40px;
        margin-top: 100px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.35);
        animation: slideDown 0.7s ease both;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-40px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    h3, p {
        color: #dce1eb;
    }

    .form-control {
        background-color: #0a192f;
        border: 1px solid #233554;
        color: white;
        height: 48px;
    }

    .form-control:focus {
        border-color: #64ffda;
        box-shadow: 0 0 10px #64ffda55;
        background-color: #0a192f;
        color: white;
    }

    .btn-custom {
        background-color: #64ffda;
        color: #0a192f;
        font-weight: bold;
        transition: 0.3s;
        height: 48px;
        border-radius: 8px;
    }

    .btn-custom:hover {
        background-color: #48d9b7;
        transform: translateY(-2px);
    }

    a {
        color: #64ffda;
    }
</style>
</head>

<body>

<div class="container h-100 d-flex justify-content-center align-items-center">
  <div class="col-md-4">
    <div class="login-box text-center">

      <h3 class="mb-4">Giriş Yap</h3>

      <?php if(!empty($login_error)): ?>
        <div class="login-error"><?= htmlspecialchars($login_error) ?></div>
      <?php endif; ?>

      <form action="login.php" method="POST">
          <div class="mb-3">
            <input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı" required>
          </div>

          <div class="mb-3">
            <input type="password" name="password" class="form-control" placeholder="Şifre" required>
          </div>

          <button class="btn btn-custom w-100 mb-3">Giriş Yap</button>

          <p class="small">Hesabın yok mu? <a href="register.php" style="color:#48cae4;">Kayıt ol</a></p>
      </form>

    </div>
  </div>
</div>

</body>
</html>
