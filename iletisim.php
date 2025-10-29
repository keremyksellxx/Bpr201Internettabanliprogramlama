<?php include 'includes/header.php'; ?>

<!--  Başlık Alanı -->
<section class="hero" style="background: linear-gradient(to right, #0a192f, #1b263b); color: white; padding: 80px 0; border-bottom: 5px solid #00b4d8;">
  <div class="container text-center">
    <h1 class="fw-bold">İletişim</h1>
    <p class="lead">Bize ulaşmak için formu doldurabilirsiniz</p>
  </div>
</section>

<!-- İLETİŞİM FORMU -->
<div id="page-content" class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card shadow">
        <div class="card-body">
          <h3 class="card-title mb-4 text-primary">Bizimle İletişime Geçin</h3>
          <form>
            <div class="mb-3">
              <label for="name" class="form-label">Adınız</label>
              <input type="text" class="form-control" id="name" placeholder="Adınızı girin">
            </div>
            <div class="mb-3">
              <label for="email" class="form-label">E-posta</label>
              <input type="email" class="form-control" id="email" placeholder="Email adresinizi girin">
            </div>
            <div class="mb-3">
              <label for="message" class="form-label">Mesajınız</label>
              <textarea class="form-control" id="message" rows="5" placeholder="Mesajınızı yazın"></textarea>
            </div>
            <button type="submit" class="btn btn-primary rounded-pill w-100">Gönder</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'includes/footer.php'; ?>
