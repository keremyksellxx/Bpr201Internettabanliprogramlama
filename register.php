<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kayıt Ol | Haber Portalı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0a192f;
      font-family: 'Segoe UI', sans-serif;
    }
    .register-box {
      background-color: #1b263b;
      border-radius: 16px;
      padding: 40px;
      margin-top: 100px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .btn-custom {
      background-color: #0a192f;
      color: white;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background-color: #00b4d8;
    }
    h3{
      color: #dce1eb;
    }
    p{
      color: #dce1eb;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="register-box">
          <h3 class="text-center mb-4">Kayıt Ol</h3>
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Ad Soyad">
            </div>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="E-Posta">
            </div>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Kullanıcı Adı">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" placeholder="Şifre">
            </div>
            <button type="submit" class="btn btn-custom w-100">Kayıt Ol</button>
            <p class="text-center mt-3 small">Zaten hesabın var mı? <a href="login.php">Giriş yap</a></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
