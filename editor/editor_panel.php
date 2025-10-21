<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>YeniHaber - Dijital Dergi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<div class="container my-5">
  <h3 class="mb-4 fw-bold">Yeni Haber Ekle</h3>
  <form class="shadow p-4 bg-light rounded">
    <div class="mb-3">
      <label class="form-label fw-semibold">Haber Başlığı</label>
      <input type="text" class="form-control" placeholder="Başlığı girin...">
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Haber İçeriği</label>
      <textarea class="form-control" rows="6" placeholder="Haber detaylarını yazın..."></textarea>
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Haber Görseli</label>
      <input type="file" class="form-control">
    </div>
    <button type="button" class="btn btn-success w-100">Yayınla</button>
  </form>
</div>

