<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Giriş Yap | Haber Portalı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #0a192f;
      color: #dce1eb;
      font-family: 'Segoe UI', sans-serif;
    }
    .login-box {
      background-color: #1b263b;
      border-radius: 16px;
      padding: 40px;
      margin-top: 100px;
      box-shadow: 0 5px 15px rgba(0,0,0,0.4);
    }
    .form-control {
      background-color: #0a192f;
      border: 1px solid #00b4d8;
      color: white;
    }
    .form-control:focus {
      border-color: #48cae4;
      box-shadow: 0 0 5px #48cae4;
    }
    .btn-custom {
      background-color: #00b4d8;
      color: white;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background-color: #0096c7;
    }
    a { color: #48cae4; }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="login-box text-center">
          <h3 class="mb-4">Giriş Yap</h3>
          <form>
            <div class="mb-3">
              <input type="text" class="form-control" placeholder="Kullanıcı Adı">
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" placeholder="Şifre">
            </div>
            <button type="submit" class="btn btn-custom w-100 mb-3">Giriş Yap</button>
            <p class="small">Hesabın yok mu? <a href="register.php">Kayıt ol</a></p>
           
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
