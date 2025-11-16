<?php
// Oturumu başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>YeniHaber - Dijital Dergi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
  <style>
    /* Eğer özel gradient navbar kullanıyorsan toggler ikonunu beyaz yap */
    .navbar-dark .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg viewBox='0 0 30 30' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke='rgba(255,255,255,1)' stroke-width='2' stroke-linecap='round' stroke-miterlimit='10' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background: rgba(10,25,47,0.9);">
  <div class="container">
    <a class="navbar-brand" href="index.php">KYHABER</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">

        <li class="nav-item"><a class="nav-link active" href="index.php">Anasayfa</a></li>
        <li class="nav-item"><a class="nav-link" href="kategori.php">Kategoriler</a></li>
        <li class="nav-item"><a class="nav-link" href="hakkimizda.php">Hakkımızda</a></li>
        <li class="nav-item"><a class="nav-link" href="iletisim.php">İletişim</a></li>

        <?php if (isset($_SESSION["user_fullname"])): ?>
            <li class="nav-item">
              <a class="nav-link text-warning fw-bold">
                Hoş geldin, <?= htmlspecialchars($_SESSION["user_fullname"]) ?>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Çıkış Yap</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Giriş</a>
            </li>
        <?php endif; ?>

      </ul>
    </div>
  </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
