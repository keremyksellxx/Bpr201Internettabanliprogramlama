<?php
require_once "db.php";
session_start();

$error = "";

// Form gönderilmişse
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    // Boş alan kontrolü
    if ($fullname === "" || $email === "" || $username === "" || $password === "") {
        $error = "Lütfen tüm alanları doldurun.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Geçerli bir e-posta adresi girin.";
    }
    else {
        try {

            // Aynı kullanıcı var mı kontrol
            $check = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
            $check->execute([$email, $username]);

            if ($check->rowCount() > 0) {
                $error = "Bu e-posta veya kullanıcı adı zaten kullanılıyor.";
            } 
            else {

                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $role = "user"; // Varsayılan rol

                // REGISTER INSERT
                $insert = $db->prepare("
                    INSERT INTO users (fullname, email, username, password, role)
                    VALUES (?, ?, ?, ?, ?)
                ");

                $result = $insert->execute([
                    $fullname,
                    $email,
                    $username,
                    $hashedPassword,
                    $role
                ]);

                if ($result) {
                    header("Location: login.php?msg=success");
                    exit();
                } else {
                    $error = "Kayıt sırasında hata oluştu.";
                }
            }
        } 
        catch (PDOException $e) {
            error_log("REGISTER ERROR: " . $e->getMessage());
            $error = "Sunucu hatası oluştu. Daha sonra tekrar deneyin.";
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
    body {
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
        from { opacity: 0; transform: translateY(-40px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    h3, p {
        color: #dce1eb;
    }

    .form-control {
        background-color: #0a192f !important;
        border: 1px solid #233554;
        color: white !important;
        height: 48px;
    }

    .form-control:focus {
        background-color: #0a192f !important;
        color: #fff !important;
        border-color: #64ffda;
        box-shadow: 0 0 10px #64ffda55;
    }

    /* Chrome autofill (beyaz arka plan) düzeltme */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #0a192f inset !important;
        -webkit-text-fill-color: #fff !important;
        transition: background-color 9999s ease-in-out 0s;
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

    a { color: #64ffda; }
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
                        <input type="text" class="form-control" name="fullname"
                        placeholder="Ad Soyad" required
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <input type="email" class="form-control" name="email"
                        placeholder="E-Posta" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" name="username"
                        placeholder="Kullanıcı Adı" required
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" name="password"
                        placeholder="Şifre" required>
                    </div>

                    <button type="submit" class="btn btn-custom w-100">Kayıt Ol</button>
                </form>

                <p class="text-center mt-3 small">
                    Zaten hesabın var mı? <a href="login.php">Giriş yap</a>
                </p>

            </div>
        </div>
    </div>
</div>

</body>
</html>
