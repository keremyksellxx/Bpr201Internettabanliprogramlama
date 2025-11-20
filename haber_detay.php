<?php
require_once "db.php";

if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id'];

// Tek bir haberi çek
$stmt = $db->prepare("
    SELECT h.*, 
           COALESCE(u.fullname, h.editor_adi, 'Admin') AS editor_adi 
    FROM haberler h 
    LEFT JOIN users u ON h.editor_id = u.id 
    WHERE h.id = ?
");
$stmt->execute([$id]);
$haber = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$haber) {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($haber['baslik']) ?></title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body {
    background-color: #f4f6f8;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  .haber-container {
    max-width: 900px;
    margin: 50px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
  }
  .haber-container img {
    border-radius: 10px;
    margin-bottom: 20px;
    max-height: 500px;
    object-fit: cover;
    width: 100%;
  }
  .haber-container h2 {
    font-weight: 700;
    color: #0a192f;
    margin-bottom: 15px;
  }
  .haber-meta {
    font-size: 0.9rem;
    color: #6c757d;
    margin-bottom: 25px;
  }
  .haber-container p {
    line-height: 1.8;
    color: #333;
    font-size: 1rem;
  }
  .btn-back {
    background-color: #0a192f;
    color: #fff;
    border-radius: 50px;
    padding: 10px 25px;
    transition: all 0.3s ease;
  }
  .btn-back:hover {
    background-color: #00b4d8;
    color: #fff;
  }
</style>
</head>
<body>

<div class="container haber-container">
    <h2><?= htmlspecialchars($haber['baslik']) ?></h2>
    <p class="haber-meta">
        Kategori: <?= htmlspecialchars($haber['kategori']) ?> | Editör: <?= htmlspecialchars($haber['editor_adi']) ?>
    </p>

    <?php if($haber['resim']): ?>
        <img src="<?= htmlspecialchars($haber['resim']) ?>" alt="Haber Görseli">
    <?php endif; ?>

    <p><?= nl2br(htmlspecialchars($haber['icerik'])) ?></p>

    <a href="index.php" class="btn btn-back mt-3">Geri</a>
</div>

</body>
</html>
