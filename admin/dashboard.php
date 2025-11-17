<?php
require_once "../db.php";

// Toplam haber
$stmtHaber = $db->query("SELECT COUNT(*) AS toplam_haber FROM haberler");
$haber = $stmtHaber->fetch(PDO::FETCH_ASSOC);

// Toplam editör
$stmtEditor = $db->query("SELECT COUNT(*) AS toplam_editor FROM users WHERE role = 'editor'");
$editor = $stmtEditor->fetch(PDO::FETCH_ASSOC);

// Toplam kategori
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
body {
    background-color: #f1f4f9;
    margin: 0;
    overflow-x: hidden;
}

/* SIDEBAR */
.sidebar {
    width: 240px;
    height: 100vh;
    background: #0a192f;
    color: white;
    position: fixed;
    top: 0;
    left: 0;
    padding: 20px;
    transition: transform .3s ease;
    z-index: 1000;
}

.sidebar a {
    color: #dce1eb;
    text-decoration: none;
    display: block;
    padding: 10px;
    border-radius: 8px;
    margin-bottom: 5px;
}

.sidebar a:hover {
    background: #1b263b;
    color: #00b4d8;
}

/* HAMBURGER */
.hamburger {
    display: none;
    font-size: 28px;
    cursor: pointer;
    color: #0a192f;
    padding: 15px;
}

/* CONTENT */
.content {
    margin-left: 260px;
    padding: 30px;
    transition: margin-left .3s ease;
}

/* Mobil Ayarlar */
@media(max-width: 768px) {
    .sidebar {
        transform: translateX(-260px);
    }
    .sidebar.active {
        transform: translateX(0);
    }
    .content {
        margin-left: 0 !important;
    }
    .hamburger {
        display: block;
    }
    .content.shift {
        margin-left: 240px !important;
    }
}
</style>

</head>
<body>

<!-- HAMBURGER -->
<span class="hamburger" onclick="toggleMenu()">
    <i class="bi bi-list"></i>
</span>

<!-- SIDEBAR -->
<div class="sidebar" id="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper me-2"></i> Haberleri Yönet</a>
    <a href="./editorler.php"><i class="bi bi-person-badge me-2"></i> Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags me-2"></i> Kategoriler</a>
    <a href="./users.php"><i class="bi bi-people me-2"></i> Kullanıcılar</a>
    <a href="../index.php"><i class="bi bi-house-door me-2"></i> Siteye Git</a>
</div>

<!-- CONTENT -->
<div class="content" id="content">
    <h2 class="text-primary mb-4">Dashboard</h2>

    <div class="row g-4">
        <div class="col-md-4 col-sm-12">
            <div class="card p-4 text-center bg-white shadow-sm">
                <h5>Toplam Haber</h5>
                <p class="display-5 text-success"><?= $haber['toplam_haber'] ?></p>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="card p-4 text-center bg-white shadow-sm">
                <h5>Toplam Editör</h5>
                <p class="display-5 text-warning"><?= $editor['toplam_editor'] ?></p>
            </div>
        </div>

        <div class="col-md-4 col-sm-12">
            <div class="card p-4 text-center bg-white shadow-sm">
                <h5>Toplam Kategori</h5>
                <p class="display-5 text-info"><?= $kategori['toplam_kategori'] ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMenu() {
    document.getElementById("sidebar").classList.toggle("active");
    document.getElementById("content").classList.toggle("shift");
}
</script>

</body>
</html>
