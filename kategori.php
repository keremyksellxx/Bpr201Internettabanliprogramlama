<?php
require_once "db.php";
include 'includes/header.php';

// URL'den kategori parametresini al
$kategori = isset($_GET['kategori']) ? trim($_GET['kategori']) : 'Dünya';

// Haberleri kategoriye göre çek (admin ve editörler için editör adını doğru göster)
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

// Tüm kategorileri çek (menü için)
$kategoriler = $db->query("SELECT * FROM kategoriler ORDER BY kategori_adi ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
/* GENEL BODY (Tüm sayfalarla aynı arka plan) */
body {
    background-color: #f1f4f9 !important; 
    font-family: 'Segoe UI', sans-serif;
    color: #0a192f;
    margin: 0;
    padding: 0;
}

/* HERO BÖLÜMÜ */
.hero {
    background: linear-gradient(to right, #f8f9fa, #e9ecef);
    color: #0a192f;
    padding: 60px 0;
    border-bottom: 3px solid #00b4d8;
    text-align: center;
}
.hero h1 {
    font-size: 2.5rem;
    margin-bottom: 10px;
}
.hero p {
    font-size: 1.1rem;
}

/* Kategori Menüsü */
.kategori-menu {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin: 30px 0;
}
.kategori-menu a {
    padding: 6px 14px;
    border-radius: 20px;
    background-color: #0a192f;
    color: #fff;
    text-decoration: none;
    transition: 0.3s;
}
.kategori-menu a:hover {
    background-color: #00b4d8;
}

/* Haber Kartları */
.haber-card {
    border: 1px solid #dee2e6;
    border-radius: 10px;
    margin-bottom: 25px;
    background-color: #ffffff;
}
.haber-card img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}
.haber-card .card-body {
    padding: 15px;
}
.card-title {
    font-size: 1.3rem;
    font-weight: 600;
}
.card-text {
    font-size: 1rem;
    color: #495057;
    margin-bottom: 10px;
}
.card-footer {
    background-color: #f8f9fa;
    font-size: 0.9rem;
}

/* Responsive */
@media(max-width:768px){
    .kategori-menu { justify-content: center; }
    .hero h1 { font-size: 2rem; }
}
</style>

<!-- HERO -->
<section class="hero text-center">
  <div class="container">
    <h1 class="fw-bold">Kategori: <?= htmlspecialchars($kategori) ?></h1>
    <p class="lead">Seçtiğiniz kategoriye ait güncel haberler</p>
  </div>
</section>

<!-- Kategori Menüsü -->
<div class="container kategori-menu">
    <?php foreach($kategoriler as $k): ?>
        <a href="kategori.php?kategori=<?= urlencode($k['kategori_adi']) ?>">
            <?= htmlspecialchars($k['kategori_adi']) ?>
        </a>
    <?php endforeach; ?>
</div>

<!-- HABERLER -->
<div class="container my-5">
    <?php if(empty($haberler)): ?>
        <div class="alert alert-info text-center">Bu kategoriye ait haber bulunamadı.</div>
    <?php else: ?>
        <?php foreach($haberler as $h): ?>
            <div class="card haber-card">
                <?php if($h['resim']): ?>
                    <img src="<?= htmlspecialchars($h['resim']) ?>" alt="Haber Görseli">
                <?php else: ?>
                    <img src="images/default.jpg" alt="Varsayılan Görsel">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($h['baslik']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars(substr($h['icerik'], 0, 200))) ?>...</p>
                    <a href="haber_detay.php?id=<?= $h['id'] ?>" class="btn btn-primary rounded-pill">Haberi Oku</a>
                </div>
                <div class="card-footer text-muted">
                    Editör: <?= htmlspecialchars($h['editor_adi']) ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
