<?php
session_start();
require_once "db.php";

// Giriş kontrol
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Mevcut kullanıcıyı çek
$stmt = $db->prepare("SELECT fullname, email, username, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Profil güncelleme
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"]);
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $new_password = trim($_POST["new_password"]);

    if ($fullname == "" || $email == "" || $username == "") {
        $error = "Tüm alanlar doldurulmalı.";
    } else {

        // Username başka biri tarafından kullanılıyor mu?
        $check = $db->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $check->execute([$username, $user_id]);

        if ($check->rowCount() > 0) {
            $error = "Bu kullanıcı adı zaten kullanılıyor!";
        } else {
            // Şifre değişiyor mu?
            if ($new_password !== "") {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $query = $db->prepare("UPDATE users SET fullname=?, email=?, username=?, password=? WHERE id=?");
                $query->execute([$fullname, $email, $username, $hashed, $user_id]);
            } else {
                $query = $db->prepare("UPDATE users SET fullname=?, email=?, username=? WHERE id=?");
                $query->execute([$fullname, $email, $username, $user_id]);
            }

            // Session güncelle
            $_SESSION["user_fullname"] = $fullname;
            $_SESSION["username"] = $username;

            $success = "Profil başarıyla güncellendi!";
        }
    }
}

// Güncel bilgiyi tekrar çek
$stmt = $db->prepare("SELECT fullname, email, username, role FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Profil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
    body {
        background: linear-gradient(135deg, #0a192f, #112240, #0a192f);
        min-height: 100vh;
        color: white;
        font-family: "Poppins", sans-serif;
        animation: fadeIn 1s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .profile-box {
        width: 95%;
        max-width: 550px;
        margin: auto;
        margin-top: 50px;
        padding: 35px;
        background: rgba(255,255,255,0.06);
        backdrop-filter: blur(12px);
        border-radius: 20px;
        box-shadow: 0 0 25px rgba(0,0,0,0.3);
        animation: slideUp 0.8s ease;
    }

    @keyframes slideUp {
        from { transform: translateY(40px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    .avatar {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: #00b4d8;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 46px;
        font-weight: bold;
        margin: auto;
        color: white;
        box-shadow: 0 0 20px rgba(0, 180, 216, 0.6);
        animation: pulse 2s infinite ease-in-out;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.08); }
        100% { transform: scale(1); }
    }

    input {
        background: rgba(255,255,255,0.1) !important;
        border: none !important;
        color: white !important;
        border-radius: 10px !important;
    }

    input::placeholder {
        color: #c8d1dd !important;
    }

    .btn-custom {
        background: #00b4d8;
        border: none;
        color: white;
        padding: 10px;
        width: 100%;
        border-radius: 12px;
        transition: 0.3s;
        font-size: 17px;
        font-weight: 600;
    }

    .btn-custom:hover {
        background: #0092ad;
        transform: translateY(-2px);
        box-shadow: 0 0 20px rgba(0,180,216,0.5);
    }

    .btn-logout {
        background: #d62828;
    }

    .btn-logout:hover {
        background: #b81f1f;
        box-shadow: 0 0 20px rgba(214,40,40,0.5);
    }
</style>
</head>

<body>

<div class="profile-box">

    <div class="avatar mb-3">
        <?= strtoupper(substr($user["fullname"], 0, 1)) ?>
    </div>

    <h3 class="text-center fw-bold"><?= $user["fullname"] ?></h3>
    <p class="text-center text-info mb-1"><i class="bi bi-person"></i> @<?= $user["username"] ?></p>
    <p class="text-center text-secondary mb-3"><i class="bi bi-person-badge"></i> Rol: <?= $user["role"] ?></p>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">

        <label class="mb-1">Kullanıcı Adı</label>
        <input class="form-control mb-3" type="text" name="username" value="<?= htmlspecialchars($user["username"]) ?>">

        <label class="mb-1">Ad Soyad</label>
        <input class="form-control mb-3" type="text" name="fullname" value="<?= htmlspecialchars($user["fullname"]) ?>">

        <label class="mb-1">Email</label>
        <input class="form-control mb-3" type="email" name="email" value="<?= htmlspecialchars($user["email"]) ?>">

        <label class="mb-1">Yeni Şifre (Opsiyonel)</label>
        <input class="form-control mb-4" type="password" name="new_password" placeholder="Yeni şifre girin (boş bırakabilirsiniz)">

        <button class="btn-custom mb-2" type="submit">
            <i class="bi bi-check2-circle"></i> Profili Güncelle
        </button>

        <a href="index.php" class="btn btn-secondary w-100 mb-2">
            <i class="bi bi-house"></i> Ana Sayfa
        </a>

        <a href="logout.php" class="btn btn-logout w-100">
            <i class="bi bi-box-arrow-right"></i> Çıkış Yap
        </a>

    </form>

</div>

</body>
</html>
