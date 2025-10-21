<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Girişi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  body { background-color: #0a192f; display: flex; justify-content: center; align-items: center; height: 100vh; }
  .card { width: 400px; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); background-color: #ffffff; }
  .card h3 { color: #0a192f; }
</style>
</head>
<body>
  <div class="card">
    <h3 class="text-center mb-4">Admin Girişi</h3>
    <form>
      <div class="mb-3">
        <label>Kullanıcı Adı</label>
        <input type="text" class="form-control" placeholder="Kullanıcı Adı">
      </div>
      <div class="mb-3">
        <label>Şifre</label>
        <input type="password" class="form-control" placeholder="Şifre">
      </div>
      <button type="submit" class="btn btn-primary w-100">Giriş Yap</button>
    </form>
  </div>
</body>
</html>
