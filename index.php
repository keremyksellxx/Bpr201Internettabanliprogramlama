<!DOCTYPE html>
<html lang="tr">
<head>
  <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>KYHABER - Gündemi Bizimle Takip Et</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <?php include 'includes/header.php'; ?>

  <style>
    body {
      background-color: #f5f7fb;
      font-family: 'Segoe UI', sans-serif;
      color: #0a192f;
    }

    /* NAVBAR */
    .navbar {
      background: rgba(10, 25, 47, 0.9);
      backdrop-filter: blur(10px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.25);
      transition: background 0.3s ease;
    }
    .navbar-brand {
      color: #ffcc00 !important;
      font-weight: 700;
      letter-spacing: 1px;
    }
    .nav-link {
      color: #dce1eb !important;
      font-weight: 500;
      margin-right: 15px;
      position: relative;
    }
    .nav-link::after {
      content: "";
      position: absolute;
      left: 0;
      bottom: 0;
      width: 0;
      height: 2px;
      background-color: #ffcc00;
      transition: width 0.3s ease;
    }
    .nav-link:hover::after {
      width: 100%;
    }

    /* HERO */
    .hero {
      position: relative;
      background: url('https://source.unsplash.com/1600x800/?news,city') center/cover no-repeat;
      color: white;
      text-align: center;
      padding: 120px 20px;
    }
    .hero::before {
      content: "";
      position: absolute;
      top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(to right, #0a192f, #112240);
    }
    .hero h1, .hero p {
      position: relative;
      z-index: 1;
    }
    .hero h1 {
      font-weight: 800;
      font-size: 3rem;
      animation: fadeInDown 1.2s ease;
    }
    .hero p {
      color: #a8b2d1;
      font-size: 1.15rem;
      margin-top: 12px;
      animation: fadeInUp 1.4s ease;
    }
    @keyframes fadeInDown {
      from { opacity: 0; transform: translateY(-20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* KARTLAR */
    .card {
      border: none;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 5px 15px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      background: #fff;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .card img {
      height: 220px;
      object-fit: cover;
    }
    .card-body {
      padding: 18px;
    }
    .card-title {
      font-weight: 600;
      color: #0a192f;
      margin-bottom: 8px;
    }
    .card-text {
      color: #6c757d;
      font-size: 0.95rem;
    }
    .btn-outline-primary {
      border-color: #ffcc00;
      color: #0a192f;
      font-weight: 500;
    }
    .btn-outline-primary:hover {
      background-color: #ffcc00;
      border-color: #ffcc00;
      color: #0a192f;
    }

    /* FOOTER */
    .footer {
      background: linear-gradient(to right, #0a192f, #112240);
      color: #dce1eb;
      text-align: center;
      padding: 25px 0;
      margin-top: 80px;
      font-size: 0.95rem;
    }
    .footer a {
      color: #ffcc00;
      text-decoration: none;
    }
    .footer a:hover {
      text-decoration: underline;
    }

  </style>
</head>
<?php
require_once "db.php";

// Haberleri çek
$stmt = $db->query("SELECT * FROM haberler ORDER BY tarih DESC");
$haberler = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>


<body>

<!-- NAVBAR -->
<<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">KYHABER</a>
    <button class="navbar-toggler text-light" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="index.php">Anasayfa</a></li>
        <li class="nav-item"><a class="nav-link" href="kategori.php">Kategoriler</a></li>
        <li class="nav-item"><a class="nav-link" href="hakkimizda.php">Hakkımızda</a></li>
        <li class="nav-item"><a class="nav-link" href="iletisim.php">İletişim</a></li>

        <?php if (isset($_SESSION["user_fullname"])): ?>
            <!-- Kullanıcı giriş yaptıysa -->
            <li class="nav-item">
              <a class="nav-link text-warning fw-bold">
                Hoş geldin, <?= htmlspecialchars($_SESSION["user_fullname"]) ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Çıkış Yap</a>
            </li>

        <?php else: ?>
            <!-- Kullanıcı giriş yapmadıysa -->
            <li class="nav-item">
              <a class="nav-link" href="login.php">Giriş</a>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>




<!-- HERO -->
<section class="hero">
  <div class="container position-relative">
    <h1>Gündemi Bizimle Takip Et</h1>
    <p>Tarafsız haberciliğin adresi. Gerçek bilgi, güvenilir kaynaklarla sizlerle.</p>
  </div>
</section>




<?php
require_once "db.php";

// Haberleri çek
$haberler = $db->query("SELECT h.*, u.fullname AS editor_adi 
                        FROM haberler h 
                        LEFT JOIN users u ON h.editor_id = u.id 
                        ORDER BY h.id DESC")
                 ->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container my-5">
    <div class="row">
        <?php foreach($haberler as $h): ?>
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
                <?php if($h['resim']): ?>
                    <img src="<?= htmlspecialchars($h['resim']) ?>" class="card-img-top" alt="Haber Görseli">
                <?php endif; ?>
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($h['baslik']) ?></h5>
                    <p class="card-text"><?= nl2br(htmlspecialchars(mb_substr($h['icerik'], 0, 120))) ?>...</p>
                    <a href="haber_detay.php?id=<?= $h['id'] ?>" class="btn btn-primary mt-auto">Devamını Oku</a>
                </div>
                <div class="card-footer text-muted small">
                    Kategori: <?= htmlspecialchars($h['kategori']) ?> <br>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>



  <div class="text-center mt-5">
    <a href="kategori.php" class="btn btn-primary px-4 py-2" style="background-color:#ffcc00; border:none; color:#0a192f;">Tüm Haberleri Gör</a>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <p>© 2025 KYHABER - Tüm Hakları Saklıdır | <a href="iletisim.php">Bize Ulaşın</a></p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
