<?php
require_once "../db.php"; // Veritabanı bağlantısı

$mesaj = "";

// Kullanıcı silme işlemi
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);
    $mesaj = "Kullanıcı başarıyla silindi!";
}

// Kullanıcıları çek
$stmt = $db->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kullanıcılar - Admin Paneli</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { background-color: #f1f4f9; font-family: 'Segoe UI', sans-serif; margin:0; padding:0; }
.sidebar {
    height: 100vh;
    background-color: #0a192f;
    color: white;
    padding: 20px;
    position: fixed;
    width: 220px;
    top:0;
    transition: all 0.3s;
    overflow-y: auto;
}
.sidebar a { color: #dce1eb; display: block; padding: 12px 0; text-decoration: none; border-radius: 5px; }
.sidebar a:hover { background-color: #1b263b; color: #00b4d8; padding-left: 10px; transition: all 0.2s; }

.content { margin-left: 240px; padding: 20px; }
.table-responsive { overflow-x: auto; }

@media(max-width:768px){
    .sidebar { position: relative; width: 100%; height: auto; }
    .content { margin-left:0; padding:10px; }
    .btn-sm { width: 100%; margin-bottom: 5px; }
}
</style>
</head>
<body>
<div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper me-2"></i> Haberler</a>
    <a href="./editorler.php"><i class="bi bi-person-badge me-2"></i> Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags me-2"></i> Kategoriler</a>
    <a href="./users.php"><i class="bi bi-people me-2"></i> Kullanıcılar</a>
    <a href="../index.php"><i class="bi bi-house-door me-2"></i> Siteye Git</a>
</div>

<div class="content">
    <h2 class="mb-4">Kullanıcılar</h2>

    <?php if(!empty($mesaj)) echo "<div class='alert alert-success'>$mesaj</div>"; ?>

    <div class="table-responsive">
    <table class="table table-striped align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Ad Soyad</th>
                <th>E-Posta</th>
                <th>Kullanıcı Adı</th>
                <th>İşlemler</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sayac = 1;
            foreach($users as $u): ?>
            <tr>
                <td><?= $sayac ?></td>
                <td><?= htmlspecialchars($u['fullname']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td>
                    <a href="?delete_id=<?= $u['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Kullanıcı silinsin mi?')">
                        <i class="bi bi-trash"></i> Sil
                    </a>
                </td>
            </tr>
            <?php $sayac++; endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
</body>
</html>
