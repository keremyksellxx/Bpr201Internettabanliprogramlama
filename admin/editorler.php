<?php
require_once "../db.php";

// Hata / başarı mesajları
$error = $success = "";

// Kullanıcı ekleme
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['add_user'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $role = $_POST['role']; // <-- BURADA DROPDOWN'DAN GELEN ROL DOĞRU ALINIYOR!

    if(empty($fullname) || empty($email) || empty($username) || empty($password)) {
        $error = "Lütfen tüm alanları doldurun!";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (fullname,email,username,password,role) VALUES (?,?,?,?,?)");
        try {
            $stmt->execute([$fullname,$email,$username,$hashedPassword,$role]);
            $success = "Yeni kullanıcı eklendi!";
        } catch(PDOException $e) {
            $error = "Kullanıcı adı veya email zaten mevcut!";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_user'])) {
    $id = intval($_POST['update_id']);
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $username = trim($_POST['username']);
    $role = isset($_POST['role']) ? $_POST['role'] : 'editor'; // role mutlaka alınıyor
    $password = trim($_POST['password']);

    try {
        if(!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET fullname=:fullname, email=:email, username=:username, role=:role, password=:password WHERE id=:id");
            $stmt->execute([
                ':fullname' => $fullname,
                ':email' => $email,
                ':username' => $username,
                ':role' => $role,
                ':password' => $hashedPassword,
                ':id' => $id
            ]);
        } else {
            $stmt = $db->prepare("UPDATE users SET fullname=:fullname, email=:email, username=:username, role=:role WHERE id=:id");
            $stmt->execute([
                ':fullname' => $fullname,
                ':email' => $email,
                ':username' => $username,
                ':role' => $role,
                ':id' => $id
            ]);
        }
        $success = "Kullanıcı güncellendi!";
    } catch(PDOException $e) {
        $error = "Güncelleme sırasında bir hata oluştu: " . $e->getMessage();
    }
}

// Kullanıcı silme
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $stmt = $db->prepare("DELETE FROM users WHERE id=?");
    $stmt->execute([$delete_id]);
    header("Location: editorler.php");
    exit();
}

// Tüm kullanıcıları çek
// Sadece admin ve editörleri listele
$users = $db->prepare("SELECT * FROM users WHERE role IN ('admin','editor')");
$users->execute();
$users = $users->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Editörler - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f1f4f9; font-family: 'Segoe UI', sans-serif; }
.sidebar {
    height: 100vh; background-color: #0a192f; color: white; padding: 20px;
    position: fixed; width: 220px; transition: all 0.3s;
}
.sidebar a { color: #dce1eb; display: block; padding: 12px 0; text-decoration: none; border-radius: 5px; }
.sidebar a:hover { background-color: #1b263b; color: #00b4d8; padding-left: 10px; transition: all 0.2s; }
.content { margin-left: 240px; padding: 40px; }
.card { border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.card:hover { transform: translateY(-5px); transition: transform 0.3s ease; }
@media(max-width:768px){
    .sidebar { position: relative; width: 100%; height: auto; }
    .content { margin-left:0; padding:20px; }
}
</style>
</head>
<body>
<div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper me-2"></i> Haberleri Yönet</a>
    <a href="./editorler.php"><i class="bi bi-person-badge me-2"></i> Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags me-2"></i> Kategoriler</a>
        <a href="./users.php"><i class="bi bi-people"></i>        &nbsp;  Kullanıcılar</a>

    <a href="../index.php"><i class="bi bi-house-door me-2"></i> Siteye Git</a>
</div>

<div class="content">
    <h2 class="mb-4">Editörler</h2>

    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- Yeni Kullanıcı Ekle -->
    <div class="card mb-4 p-3">
        <h5>Yeni Kullanıcı Ekle</h5>
        <form method="POST" class="row g-2">
            <input type="hidden" name="add_user" value="1">
            <div class="col-md-3"><input type="text" name="fullname" placeholder="Ad Soyad" class="form-control" required></div>
            <div class="col-md-3"><input type="email" name="email" placeholder="Email" class="form-control" required></div>
            <div class="col-md-2"><input type="text" name="username" placeholder="Kullanıcı Adı" class="form-control" required></div>
            <div class="col-md-2"><input type="password" name="password" placeholder="Şifre" class="form-control" required></div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="editor">Editör</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-12"><button type="submit" class="btn btn-warning mt-2">Yeni Kullanıcı Ekle</button></div>
        </form>
    </div>

    <!-- Mevcut Kullanıcılar Kart -->
    <div class="row g-3">
        <?php $counter=1; foreach($users as $u): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card p-3">
                <form method="POST">
                    <input type="hidden" name="update_user" value="1">
                    <input type="hidden" name="update_id" value="<?= $u['id'] ?>">
                    <h5>#<?= $counter++ ?> - <?= htmlspecialchars($u['fullname']) ?></h5>
                    <div class="mb-2">
                        <input type="text" name="fullname" value="<?= htmlspecialchars($u['fullname']) ?>" class="form-control mb-1" placeholder="Ad Soyad">
                        <input type="email" name="email" value="<?= htmlspecialchars($u['email']) ?>" class="form-control mb-1" placeholder="Email">
                        <input type="text" name="username" value="<?= htmlspecialchars($u['username']) ?>" class="form-control mb-1" placeholder="Kullanıcı Adı">
                        <select name="role" class="form-select mb-1">
                            <option value="editor" <?= $u['role']=='editor'?'selected':'' ?>>Editör</option>
                            <option value="admin" <?= $u['role']=='admin'?'selected':'' ?>>Admin</option>
                        </select>
                        <input type="password" name="password" class="form-control mb-1" placeholder="Yeni Şifre (Opsiyonel)">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm me-1">Güncelle</button>
                    <a href="?delete=<?= $u['id'] ?>" onclick="return confirm('Silmek istediğinize emin misiniz?')" class="btn btn-danger btn-sm">Sil</a>
                </form>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
