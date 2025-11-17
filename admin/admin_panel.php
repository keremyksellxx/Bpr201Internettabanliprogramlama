<?php
require_once "../db.php"; // Veritabanı bağlantısı

// Toplam haber sayısı
$stmtHaber = $db->query("SELECT COUNT(*) AS toplam_haber FROM haberler");
$haber = $stmtHaber->fetch(PDO::FETCH_ASSOC);

// Toplam editör sayısı (role = 'editor' olan kullanıcılar)
$stmtEditor = $db->query("SELECT COUNT(*) AS toplam_editor FROM users WHERE role = 'editor'");
$editor = $stmtEditor->fetch(PDO::FETCH_ASSOC);

// Toplam kategori sayısı
$stmtKategori = $db->query("SELECT COUNT(*) AS toplam_kategori FROM kategoriler");
$kategori = $stmtKategori->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #f1f4f9; }
    .sidebar {
      height: 100vh;
      background-color: #0a192f;
      color: white;
      padding: 20px;
      position: fixed;
      width: 220px;
    }
    .sidebar a {
      color: #dce1eb;
      display: block;
      padding: 12px 0;
      text-decoration: none;
      border-radius: 5px;
    }
    .sidebar a:hover {
      background-color: #1b263b;
      color: #00b4d8;
      padding-left: 10px;
      transition: all 0.2s;
    }
    .content {
      margin-left: 240px;
      padding: 40px;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.1);
      transition: transform 0.3s ease;
    }
 a{
color: black;
text-decoration: none;
 }
    .card:hover { transform: translateY(-5px); }
  </style>
</head>
<body>
  <div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper me-2"></i> Haberleri Yönet</a>
    <a href="./editorler.php"><i class="bi bi-person-badge me-2"></i> Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags me-2"></i> Kategoriler</a>
    <a href="./users.php"><i class="bi bi-people"></i> Kullanıcılar</a>

    <a href="../index.php"><i class="bi bi-house-door me-2"></i> Siteye Git</a>
  </div>

  <div class="content">
    <h2 class="text-primary mb-4">Dashboard</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 text-center bg-white">
          <h5>Toplam Haber</h5>
          <p class="display-5 text-success"><?= $haber['toplam_haber'] ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center bg-white">
          <h5>Toplam Editör</h5>
          <p class="display-5 text-warning"><?= $editor['toplam_editor'] ?></p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center bg-white">
          <h5>Toplam Kategori</h5>
          <p class="display-5 text-info"><?= $kategori['toplam_kategori'] ?></p>
        </div>
      </div>
    </div>
        <div class="card mt-5 p-3">
      <h4 class="mb-3">Hızlı İşlemler</h4>
      <div class="d-flex gap-3 flex-wrap">
        <button class="btn btn-primary" ><i class="bi bi-newspaper me-1" ></i><a href="haberler.php"> Haber Ekle</a></button>
        <button class="btn btn-warning"><i class="bi bi-person-plus me-1"></i><a href="editorler.php"> Editör Ekle </a></button>
        <button class="btn btn-info"><i class="bi bi-tags me-1"></i><a href="kategoriler.php">Kategori Ekle</a> </button>
      </div>
    </div>
  </div>





  </div>

</body>
</html>
