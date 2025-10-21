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
    .card:hover { transform: translateY(-5px); }
  </style>
</head>
<body>
  <div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="admin/dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="admin/haberler.php"><i class="bi bi-newspaper me-2"></i> Haberleri Yönet</a>
    <a href="admin/editorler.php"><i class="bi bi-person-badge me-2"></i> Editörler</a>
    <a href="admin/kategoriler.php"><i class="bi bi-tags me-2"></i> Kategoriler</a>
    <a href="../index.php"><i class="bi bi-house-door me-2"></i> Siteye Git</a>
  </div>

  <div class="content">
    <h2 class="text-primary mb-4">Dashboard</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card p-4 text-center bg-white">
          <h5>Toplam Haber</h5>
          <p class="display-5 text-success">125</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center bg-white">
          <h5>Toplam Editör</h5>
          <p class="display-5 text-warning">12</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card p-4 text-center bg-white">
          <h5>Toplam Kategori</h5>
          <p class="display-5 text-info">8</p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
