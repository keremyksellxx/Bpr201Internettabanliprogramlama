<?php
require_once "../db.php";
session_start();

// Admin kontrolü
if(!isset($_SESSION["admin_user"])) {
    header("Location: admin_login.php");
    exit();
}

$success = $error = "";

/* ===============================
   KATEGORİ GÜNCELLEME İŞLEMİ
   =============================== */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {

    $id = (int)$_POST['update_id'];
    $kategori_adi = trim($_POST['kategori_adi']);

    if(!empty($kategori_adi)) {

        $stmt = $db->prepare("UPDATE kategoriler SET kategori_adi = ? WHERE id = ?");
        $stmt->execute([$kategori_adi, $id]);

        $success = "Kategori adı başarıyla güncellendi!";
    } else {
        $error = "Kategori adı boş bırakılamaz!";
    }
}

/* ===============================
   KATEGORİ EKLEME
   =============================== */
if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['yeni_kategori'])) {

    $kategori_adi = trim($_POST['yeni_kategori']);

    if(!empty($kategori_adi)) {

        $stmt = $db->prepare("INSERT INTO kategoriler (kategori_adi) VALUES (?)");
        $stmt->execute([$kategori_adi]);

        $success = "Kategori başarıyla eklendi!";
    } else {
        $error = "Kategori adı boş olamaz!";
    }
}

/* ===============================
   KATEGORİ SİLME
   =============================== */
if(isset($_GET['sil'])) {
    $id = (int)$_GET['sil'];
    $stmt = $db->prepare("DELETE FROM kategoriler WHERE id = ?");
    $stmt->execute([$id]);

    header("Location: kategoriler.php");
    exit();
}

/* ===============================
   KATEGORİLERİ ÇEK
   =============================== */
$kategoriler = $db->query("SELECT * FROM kategoriler ORDER BY id ASC")->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kategoriler - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body { background-color: #f1f4f9; font-family: 'Segoe UI', sans-serif; }
    .sidebar { height: 100vh; background-color: #0a192f; color:white; padding:20px; position:fixed; width:220px; }
    .sidebar a { color:#dce1eb; text-decoration:none; display:block; padding:10px 0; }
    .sidebar a:hover { background-color:#1b263b; padding-left:10px; transition:.2s; color:#00b4d8; }
    .content { margin-left:240px; padding:40px; }
    .card { border-radius:10px; }
</style>
</head>
<body>

<div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper"></i> Haberler</a>
    <a href="./editorler.php"><i class="bi bi-person-badge"></i> Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags"></i> Kategoriler</a>
    <a href="./users.php"><i class="bi bi-people"></i> Kullanıcılar</a>
    <a href="../index.php"><i class="bi bi-house-door"></i> Siteye Git</a>
</div>

<div class="content">

    <h2 class="text-info mb-4">Kategoriler</h2>

    <?php if(!empty($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <?php if(!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

    <!-- Yeni Kategori Ekle -->
    <form method="POST" class="mb-3">
        <div class="input-group">
            <input type="text" name="yeni_kategori" class="form-control" placeholder="Yeni kategori ekle..." required>
            <button class="btn btn-info" type="submit">Ekle</button>
        </div>
    </form>

    <!-- Kategoriler Tablosu -->
    <div class="card p-3">
        <table class="table table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Kategori Adı</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php $sira = 1; foreach($kategoriler as $k): ?>
                <tr>
                    <form method="POST">
                        <td><?= $sira++ ?></td>

                        <!-- GÜNCELLENEBİLİR KATEGORİ ADI -->
                        <td>
                            <input type="text" class="form-control" name="kategori_adi"
                                   value="<?= htmlspecialchars($k['kategori_adi']) ?>">
                            <input type="hidden" name="update_id" value="<?= $k['id'] ?>">
                        </td>

                        <td>
                            <button class="btn btn-primary btn-sm" type="submit">
                                <i class="bi bi-save"></i> Kaydet
                            </button>

                            <a href="?sil=<?= $k['id'] ?>"
                               onclick="return confirm('Silmek istediğine emin misin?')"
                               class="btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>

                    </form>
                </tr>
                <?php endforeach; ?>

                <?php if(empty($kategoriler)): ?>
                <tr>
                    <td colspan="3" class="text-center">Kategori bulunamadı.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
