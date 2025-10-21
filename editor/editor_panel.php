<?php include("../includes/header.php"); ?>

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

<?php include("../includes/footer.php"); ?>
