<?php
require_once "db.php";

// Hata kontrolü için değişken
$error = "";

// Form gönderilmişse
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Form verilerini al ve temizle
    $fullname = trim($_POST["fullname"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // 1) Boş alan kontrolü
    if ($fullname === "" || $email === "" || $username === "" || $password === "") {
        $error = "Lütfen tüm alanları doldurun.";
    }

    // 2) Email format kontrolü
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi girin.";
    }

    else {

        try {
            // 3) Email veya kullanıcı adı var mı?
            $check = $db->prepare("SELECT id FROM users WHERE email = :email OR username = :username LIMIT 1");
            $check->execute([
                ':email' => $email,
                ':username' => $username
            ]);

            if ($check->rowCount() > 0) {
                $error = "Bu e-posta veya kullanıcı adı zaten kullanılıyor.";
            } else {

                // 4) Şifreyi hashle
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // 5) Rol kesin olarak 'kullanıcı' olacak
                $role = "kullanıcı";

                // 6) Veritabanına kayıt
                $insert = $db->prepare("
                    INSERT INTO users (fullname, email, username, password, role) 
                    VALUES (:fullname, :email, :username, :password, :role)
                ");

                $result = $insert->execute([
                    ':fullname' => $fullname,
                    ':email'    => $email,
                    ':username' => $username,
                    ':password' => $hashedPassword,
                    ':role'     => $role
                ]);

                if ($result) {
                    // Başarılı ise login'e gönder
                    header("Location: login.php?msg=success");
                    exit();
                } else {
                    $error = "Kayıt sırasında beklenmeyen bir hata oluştu.";
                }
            }
        } 
        catch (PDOException $e) {
            // Log kaydı
            error_log("REGISTER ERROR: " . $e->getMessage());
            $error = "Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kayıt Ol | Haber Portalı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #0a192f; font-family: 'Segoe UI', sans-serif; }
    .register-box {
      background-color: #1b263b; border-radius: 16px; padding: 40px;
      margin-top: 100px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background-color: #0a192f; color: white; transition: 0.3s;
    }
    .btn-custom:hover { background-color: #00b4d8; }
    h3, p { color: #dce1eb; }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="register-box">
          <h3 class="text-center mb-4">Kayıt Ol</h3>

          <?php if(!empty($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <form action="register.php" method="POST">
    <div class="mb-3">
        <input type="text" class="form-control" name="fullname" placeholder="Ad Soyad"
               required value="<?= isset($_POST['fullname']) ? htmlspecialchars($_POST['fullname']) : '' ?>">
    </div>

    <div class="mb-3">
        <input type="email" class="form-control" name="email" placeholder="E-Posta"
               required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    </div>

    <div class="mb-3">
        <input type="text" class="form-control" name="username" placeholder="Kullanıcı Adı"
               required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
    </div>

    <div class="mb-3">
        <input type="password" class="form-control" name="password" placeholder="Şifre" required>
    </div>

    <button type="submit" class="btn btn-custom w-100">Kayıt Ol</button>
</form>


          <p class="text-center mt-3 small">Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
