<?php
require_once "../db.php";

$mesaj = $error = "";

// Admin kimliği (sabit)
$admin_id = 1;
$admin_name = "Admin";

// TÜM KATEGORİLERİ ÇEK
$kategoriler = $db->query("SELECT * FROM kategoriler ORDER BY kategori_adi ASC")
                  ->fetchAll(PDO::FETCH_ASSOC);

// ==== DOSYA YÜKLEME FONKSİYONU ====
function resimYukle($file_input) {
    if (!isset($_FILES[$file_input]) || $_FILES[$file_input]['error'] !== 0) {
        return null;
    }

    $izinli = ["image/jpeg", "image/png", "image/gif"];
    if (!in_array($_FILES[$file_input]["type"], $izinli)) {
        return null;
    }

    $dosyaAdi = time() . "_" . basename($_FILES[$file_input]["name"]);
    $yol = "../uploads/" . $dosyaAdi;

    if (move_uploaded_file($_FILES[$file_input]["tmp_name"], $yol)) {
        return "uploads/" . $dosyaAdi;
    }
    return null;
}

// HABER EKLEME
if (isset($_POST['add_haber'])) {

    $baslik   = trim($_POST['baslik']);
    $icerik   = trim($_POST['icerik']);
    $kategori = trim($_POST['kategori'] ?? 'Genel');
    $resimUrl = trim($_POST['resim']);

    // Dosya yüklendiyse dosya öncelikli
    $yuklenen_dosya = resimYukle("resim_dosya");
    $resim = $yuklenen_dosya ? $yuklenen_dosya : $resimUrl;

    $stmt = $db->prepare("INSERT INTO haberler 
        (kategori, baslik, icerik, resim, editor_id, editor_adi) 
        VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->execute([
        $kategori,
        $baslik,
        $icerik,
        $resim,
        $admin_id,
        $admin_name
    ]);

    $mesaj = "Haber başarıyla admin tarafından eklendi!";
}

// HABER SİLME
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM haberler WHERE id = ?");
    $stmt->execute([$delete_id]);
    $mesaj = "Haber başarıyla silindi!";
}

// HABER GÜNCELLEME
if (isset($_POST['update_haber'])) {

    $id       = (int)$_POST['update_id'];
    $baslik   = trim($_POST['baslik']);
    $icerik   = trim($_POST['icerik']);
    $kategori = trim($_POST['kategori']);
    $resimUrl = trim($_POST['resim']);

    // resim dosya geldiyse
    $yeni_resim = resimYukle("resim_dosya_update_$id");
    $resim = $yeni_resim ? $yeni_resim : $resimUrl;

    $stmt = $db->prepare("UPDATE haberler 
        SET baslik=?, icerik=?, kategori=?, resim=?, editor_adi=? 
        WHERE id=?"
    );
    $stmt->execute([
        $baslik,
        $icerik,
        $kategori,
        $resim,
        $admin_name,
        $id
    ]);

    $mesaj = "Haber başarıyla admin tarafından güncellendi!";
}

// TÜM HABERLER
$haberler = $db->query("SELECT * FROM haberler ORDER BY id DESC")
               ->fetchAll(PDO::FETCH_ASSOC);
?>
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
    overflow-y: auto;
}
.sidebar a { color: #dce1eb; display: block; padding: 12px 0; text-decoration: none; border-radius: 5px; }
.sidebar a:hover { background-color: #1b263b; color: #00b4d8; padding-left: 10px; }

.content { margin-left: 240px; padding: 20px; }
.card { margin-bottom: 20px; }

@media(max-width:768px){
    .sidebar { width: 100%; height: auto; position: relative; }
    .content { margin-left: 0; }
}
</style>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Haberler Yönetimi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper me-2"></i> Haberleri Yönet</a>
    <a href="./editorler.php"><i class="bi bi-person-badge me-2"></i> Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags me-2"></i> Kategoriler</a>
    <a href="./users.php"><i class="bi bi-people me-2"></i> Kullanıcılar</a>
    <a href="../index.php"><i class="bi bi-house-door me-2"></i> Siteye Git</a>
</div>
<div class="content">

    <h2>Haberler Yönetimi</h2>

    <?php if ($mesaj): ?>
        <div class="alert alert-success"><?= $mesaj ?></div>
    <?php endif; ?>

    <!-- HABER EKLEME FORMU -->
    <div class="card p-3 mb-4">
        <h4>Yeni Haber Ekle</h4>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-2">
                <input type="text" class="form-control" name="baslik" placeholder="Başlık" required>
            </div>
            <div class="mb-2">
                <textarea class="form-control" name="icerik" placeholder="İçerik" rows="3" required></textarea>
            </div>

            <div class="mb-2">
                <select name="kategori" class="form-control" required>
                    <option value="">Kategori Seç...</option>
                    <?php foreach($kategoriler as $k): ?>
                        <option value="<?= $k['kategori_adi'] ?>"><?= $k['kategori_adi'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- URL veya Dosya -->
            <div class="mb-2">
                <input type="text" class="form-control" name="resim" placeholder="Resim URL (opsiyonel)">
            </div>

            <div class="mb-2">
                <input type="file" name="resim_dosya" class="form-control">
            </div>

            <button type="submit" name="add_haber" class="btn btn-success w-100">Ekle</button>
        </form>
    </div>

    <!-- HABER TABLOSU -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Başlık</th>
                    <th>İçerik</th>
                    <th>Kategori</th>
                    <th>Resim</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php $s = 1; foreach ($haberler as $h): ?>
                <tr>
                    <form method="POST" enctype="multipart/form-data">
                        <td><?= $s ?><input type="hidden" name="update_id" value="<?= $h['id'] ?>"></td>

                        <td><input type="text" name="baslik" class="form-control" value="<?= htmlspecialchars($h['baslik']) ?>"></td>

                        <td><textarea name="icerik" class="form-control"><?= htmlspecialchars($h['icerik']) ?></textarea></td>

                        <td>
                            <select name="kategori" class="form-control">
                                <?php foreach($kategoriler as $k): ?>
                                    <option value="<?= $k['kategori_adi'] ?>" <?= ($k['kategori_adi'] == $h['kategori']) ? 'selected' : '' ?>>
                                        <?= $k['kategori_adi'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>

                        <td>
                            <input type="text" name="resim" class="form-control mb-1" value="<?= htmlspecialchars($h['resim']) ?>">
                            <input type="file" name="resim_dosya_update_<?= $h['id'] ?>" class="form-control">
                        </td>

                        <td>
                            <button type="submit" name="update_haber" class="btn btn-primary btn-sm w-100 mb-1">Güncelle</button>
                            <a href="?delete_id=<?= $h['id'] ?>" class="btn btn-danger btn-sm w-100"
                               onclick="return confirm('Bu haber silinsin mi?')">Sil</a>
                        </td>
                    </form>
                </tr>
                <?php $s++; endforeach; ?>
            </tbody>
        </table>
    </div>

</div>

</body>
</html>
