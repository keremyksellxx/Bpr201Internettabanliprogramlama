<?php
// yeni_haber.php  (veya bulunduğu dosya adı)
session_start();
require_once "../db.php";

// Rol kontrolü: editör girişi zorunlu
if (!isset($_SESSION['editor_user']) || ($_SESSION['role'] ?? '') !== 'editor') {
    header("Location: editor_login.php");
    exit();
}

// Editör id
$editor_id = (int)($_SESSION['editor_id'] ?? 0);

// Mesajlar
$mesaj = "";
$error = "";

// Upload klasörü (sunucuda yazılabilir olmalı)
$uploadDir = __DIR__ . "/../uploads/"; // gerçek dosya sistemi path
$publicUploadDir = "uploads/";         // veritabanına kaydedeceğimiz path (site kökünden erişim)

// Kategorileri çek (dropdown için)
try {
    $stmt = $db->query("SELECT id, kategori_adi FROM kategoriler ORDER BY kategori_adi ASC");
    $kategoriler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $kategoriler = [];
}

// POST işleme
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Temizlik
    $baslik = trim($_POST['baslik'] ?? '');
    $icerik = trim($_POST['icerik'] ?? '');
    $kategori_id = isset($_POST['kategori_id']) ? (int)$_POST['kategori_id'] : 0;
    $resim = null;

    // Basit doğrulamalar
    if ($baslik === '' || $icerik === '') {
        $error = "Başlık ve içerik boş olamaz.";
    } else {
        $kategoriAdi = 'Dünya';
        if ($kategori_id > 0) {
            // seçilen kategori adını DB'den al (güvenlik)
            $stmt = $db->prepare("SELECT kategori_adi FROM kategoriler WHERE id = ? LIMIT 1");
            $stmt->execute([$kategori_id]);
            $k = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($k) {
                $kategoriAdi = $k['kategori_adi'];
            }
        } elseif (!empty($kategoriler)) {
            // Eğer dropdown boş değil ve hiçbir kategori seçilmediyse varsayılan olarak ilk kategori seçilebilir
            $kategoriAdi = $kategoriler[0]['kategori_adi'];
        }

        // ---- Resim işleme ----
        // Öncelik: kullanıcı resim URL'si girdi ise onu kullan (basit doğrulama)
        if (!empty($_POST['resim_url'])) {
            $resim_url = trim($_POST['resim_url']);
            // Basit URL doğrulama (isteğe göre daha sıkılaştır)
            if (filter_var($resim_url, FILTER_VALIDATE_URL)) {
                $resim = $resim_url;
            } else {
                $error = "Geçersiz resim URL'si.";
            }
        }

        // Eğer hata yoksa dosya yüklemeyi dene
        if ($error === '' && empty($resim) && isset($_FILES['resim_upload']) && $_FILES['resim_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['resim_upload'];

            // Hataları kontrol et
            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = "Dosya yükleme hatası: " . $file['error'];
            } else {
                // Maks 5 MB (isteğe göre değiştir)
                $maxSize = 5 * 1024 * 1024;
                if ($file['size'] > $maxSize) {
                    $error = "Resim 5MB'den büyük olmamalı.";
                } else {
                    // Uzantı ve MIME kontrolü
                    $finfo = new finfo(FILEINFO_MIME_TYPE);
                    $mime  = $finfo->file($file['tmp_name']);
                    $allowedMimes = [
                        'image/jpeg' => 'jpg',
                        'image/pjpeg' => 'jpg',
                        'image/png'  => 'png',
                        'image/gif'  => 'gif'
                    ];
                    if (!array_key_exists($mime, $allowedMimes)) {
                        $error = "Sadece JPG, PNG veya GIF dosyaları kabul edilir.";
                    } else {
                        // Güvenli dosya adı oluştur
                        $ext = $allowedMimes[$mime];
                        $yeniAd = bin2hex(random_bytes(8)) . "." . $ext;

                        // Hedef klasör var mı kontrol et
                        if (!is_dir($uploadDir)) {
                            // dene oluşturmayı
                            @mkdir($uploadDir, 0755, true);
                        }

                        $hedef = rtrim($uploadDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $yeniAd;
                        if (!move_uploaded_file($file['tmp_name'], $hedef)) {
                            $error = "Dosya sunucuya taşınamadı.";
                        } else {
                            // Veritabanına kaydedilecek halka açık yol
                            $resim = $publicUploadDir . $yeniAd;
                        }
                    }
                }
            }
        }

        // Eğer hala hata yoksa veritabanına ekle
        if ($error === '') {
            try {
                $stmt = $db->prepare("INSERT INTO haberler (kategori, baslik, icerik, resim, editor_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
                $stmt->execute([$kategoriAdi, $baslik, $icerik, $resim, $editor_id]);

                // Başarılı -> Redirect ile POST tekrarını engelle
                $_SESSION['flash_success'] = "Haber başarıyla eklendi!";
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit();
            } catch (PDOException $e) {
                // Loglama (hata detayını göstermeyiz)
                error_log("Haber ekleme hatası: " . $e->getMessage());
                $error = "Haber kaydedilirken bir hata oluştu.";
            }
        }
    }
}

// Flash mesaj gösterimi
if (isset($_SESSION['flash_success'])) {
    $mesaj = $_SESSION['flash_success'];
    unset($_SESSION['flash_success']);
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="utf-8">
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

    <?php if($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form class="shadow p-4 bg-light rounded" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label fw-semibold">Haber Başlığı</label>
            <input type="text" class="form-control" name="baslik" placeholder="Başlığı girin..." required value="<?= htmlspecialchars($_POST['baslik'] ?? '') ?>">
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Haber İçeriği</label>
            <textarea class="form-control" name="icerik" rows="8" placeholder="Haber detaylarını yazın..." required><?= htmlspecialchars($_POST['icerik'] ?? '') ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Kategori</label>
            <select name="kategori_id" class="form-select">
                <?php foreach ($kategoriler as $kat): ?>
                    <option value="<?= (int)$kat['id'] ?>" <?= (isset($_POST['kategori_id']) && (int)$_POST['kategori_id'] === (int)$kat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($kat['kategori_adi']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label fw-semibold">Haber Görseli (URL veya dosya yükle)</label>
            <input type="text" class="form-control mb-2" name="resim_url" placeholder="Resim URL'si girin..." value="<?= htmlspecialchars($_POST['resim_url'] ?? '') ?>">
            <input type="file" class="form-control" name="resim_upload" accept="image/*">
            <div class="form-text">Dosya seçilirse URL gözardı edilir. Maks 5MB. JPG/PNG/GIF.</div>
        </div>

        <button type="submit" class="btn btn-success w-100">Yayınla</button>
    </form>



</div>
</body>
</html>
