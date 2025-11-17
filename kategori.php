<?php
require_once "db.php";
include 'includes/header.php';

// Kategori seçimi (varsayılan Dünya)
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : 'Dünya';

// Haberleri çek
$stmt = $db->prepare("
    SELECT h.*, 
           COALESCE(u.fullname, h.editor_adi, 'Admin') AS editor_adi
    FROM haberler h
    LEFT JOIN users u ON h.editor_id = u.id
    WHERE h.kategori = ?
    ORDER BY h.id DESC
");
$stmt->execute([$kategori]);
$haberler = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Kategoriler
$kategoriler = $db->query("SELECT kategori_adi FROM kategoriler ORDER BY kategori_adi ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body {
    background-color: #f1f4f9 !important;
    font-family: 'Segoe UI', sans-serif;
}

/* HERO */
/* HERO */
.hero {
    background: linear-gradient(135deg, #0a192f 0%, #0d2238 50%, #0a192f 100%);
    padding: 60px 0;
    text-align: center;
    color: #ffffff;
    border-bottom: 4px solid #00b4d8;
    position: relative;
    z-index: 2;
    box-shadow: 0 4px 20px rgba(0,0,0,0.25);
}

.hero h1 {
    font-size: 2.6rem;
    font-weight: 700;
    letter-spacing: 1px;
    color: #00b4d8;
    text-shadow: 0 0 10px rgba(0,180,216,0.5);
}

.hero p {
    font-size: 1.2rem;
    margin-top: 10px;
    opacity: 0.9;
    color: #e2e8f0;
}


/* Kategori Menü */
.kategori-menu {
    display: flex;
    flex-wrap: wrap;
    text-align: center;
    gap: 12px;
    margin: 30px 0;
    position: relative;
    z-index: 9999 !important;
}

.kategori-menu a {
    padding: 8px 18px;
    background: #0a192f;
    color: #fff;
    border-radius: 20px;
    text-decoration: none;
    transition: 0.3s;
}

.kategori-menu a:hover {
    background: #00b4d8;
}

/* Haber Kartları */
.haber-card {
    border-radius: 10px;
    border: 1px solid #ddd;
    overflow: hidden;
    margin-bottom: 25px;
}

.haber-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
}
</style>

<!-- HERO -->
<section class="hero">
    <h1>Kategori: <?= htmlspecialchars($kategori) ?></h1>
    <p>Bu kategoriye ait haberler</p>
</section>

<div class="container kategori-menu">
    <?php foreach($kategoriler as $k): 
        $aktifMi = ($kategori === $k['kategori_adi']) ? "active" : "";
    ?>
        <a class="<?= $aktifMi ?>" 
           href="kategori.php?kategori=<?= urlencode($k['kategori_adi']) ?>">
            <?= htmlspecialchars($k['kategori_adi']) ?>
        </a>
    <?php endforeach; ?>
</div>


<!-- HABERLER -->
<div class="container my-4">
    <?php if(empty($haberler)): ?>
        <div class="alert alert-info">Bu kategoriye ait haber bulunamadı.</div>
    <?php else: ?>
        <?php foreach($haberler as $h): ?>
            <div class="card haber-card">

                <img src="<?= htmlspecialchars($h['resim'] ?: 'images/default.jpg') ?>" alt="Haber Resmi">

                <div class="card-body">
                    <h5><?= htmlspecialchars($h['baslik']) ?></h5>
                    <p><?= htmlspecialchars(substr($h['icerik'], 0, 200)) ?>...</p>

                    <a href="haber_detay.php?id=<?= $h['id'] ?>" class="btn btn-primary rounded-pill">
                        Haberi Oku
                    </a>
                </div>

                <div class="card-footer text-muted">
                    Editör: <?= htmlspecialchars($h['editor_adi']) ?>
                </div>

            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
