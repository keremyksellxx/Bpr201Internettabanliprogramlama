<?php
session_start();
require_once "../db.php";

// Rol kontrolü: editör girişi zorunlu
if (!isset($_SESSION['editor_user']) || $_SESSION['role'] !== 'editor') {
    header("Location: editor_login.php");
    exit();
}

// Doğru editor ID
$editor_id = $_SESSION['editor_id'];

// Editör sadece kendi haberlerini görür
$stmt = $db->prepare("SELECT * FROM haberler WHERE editor_id = ? ORDER BY id DESC");
$stmt->execute([$editor_id]);
$haberler = $stmt->fetchAll(PDO::FETCH_ASSOC);

$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $baslik = trim($_POST['baslik']);
    $icerik = trim($_POST['icerik']);
    $kategori = trim($_POST['kategori'] ?? 'Genel');

    // Resim işleme
    $resim = null;

    // URL girilmişse
    if (!empty($_POST['resim_url'])) {
        $resim = trim($_POST['resim_url']);
    }
    // Dosya yüklenmişse
    elseif (isset($_FILES['resim_upload']) && $_FILES['resim_upload']['error'] === 0) {
        $ext = strtolower(pathinfo($_FILES['resim_upload']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];

        if (in_array($ext, $allowed)) {
            $yeniAd = uniqid() . "." . $ext;
            $hedefKlasor = "../uploads/" . $yeniAd;

            if (move_uploaded_file($_FILES['resim_upload']['tmp_name'], $hedefKlasor)) {
                $resim = "uploads/" . $yeniAd;
            }
        }
    }

    // Veritabanına haber ekleme
    $stmt = $db->prepare("INSERT INTO haberler (kategori, baslik, icerik, resim, editor_id) VALUES (?,?,?,?,?)");
    $stmt->execute([$kategori, $baslik, $icerik, $resim, $editor_id]);

    $mesaj = "Haber başarıyla eklendi!";
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Yeni Haber Ekle - Editör</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <h3 class="mb-4 fw-bold">Yeni Haber Ekle</h3>

    <?php if($mesaj): ?>
        <div class="alert alert-success"><?= htmlspecialchars($mesaj) ?></div>
    <?php endif; ?>

    <form class="shadow p-4 bg-light rounded" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-semibold">Haber Başlığı</label>
            <input type="text" class="form-control" name="baslik" placeholder="Başlığı girin..." required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Haber İçeriği</label>
            <textarea class="form-control" name="icerik" rows="6" placeholder="Haber detaylarını yazın..." required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <input type="text" class="form-control" name="kategori" placeholder="Kategori girin...">
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Haber Görseli</label>
            <input type="text" class="form-control mb-2" name="resim_url" placeholder="Resim URL'si girin...">
            <input type="file" class="form-control" name="resim_upload">
        </div>
        <button type="submit" class="btn btn-success w-100">Yayınla</button>
    </form>
</div>
</body>
</html>
