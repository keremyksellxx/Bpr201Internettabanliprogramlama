<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>YeniHaber - Dijital Dergi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top">
  <div class="container">
    <a class="navbar-brand" href="index.php">YeniHaber</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link active" href="index.php">Anasayfa</a></li>
        <li class="nav-item"><a class="nav-link" href="kategori.php">Kategoriler</a></li>
        <li class="nav-item"><a class="nav-link" href="hakkimizda.php">Hakkımızda</a></li>
        <li class="nav-item"><a class="nav-link" href="iletisim.php">İletişim</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Giriş</a></li>
      </ul>
    </div>
  </div>
  <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="kategoriDropdown" role="button" data-bs-toggle="dropdown">
    Kategoriler
  </a>
  <ul class="dropdown-menu" aria-labelledby="kategoriDropdown">
    <li><a class="dropdown-item" href="kategori.php?kategori=teknoloji">Teknoloji</a></li>
    <li><a class="dropdown-item" href="kategori.php?kategori=spor">Spor</a></li>
    <li><a class="dropdown-item" href="kategori.php?kategori=magazin">Magazin</a></li>
  </ul>
</li>

</nav>

<div style="height: 80px;"></div> <!-- Navbar boşluğu -->
