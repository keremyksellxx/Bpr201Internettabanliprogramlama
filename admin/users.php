<?php
require_once "../db.php";

$mesaj = "";

/* --- Kullanıcı silme işlemi --- */
if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);
    $mesaj = "Kullanıcı başarıyla silindi!";
}

/* --- Kullanıcı güncelleme işlemi (POST) --- */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["edit_user"])) {

    $id       = $_POST["id"];
    $fullname = trim($_POST["fullname"]);
    $email    = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    $role     = trim($_POST["role"]);

    if ($password !== "") {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $update = $db->prepare("
            UPDATE users SET fullname=?, email=?, username=?, password=?, role=? WHERE id=?
        ");
        $update->execute([$fullname, $email, $username, $hashed, $role, $id]);
    } else {
        $update = $db->prepare("
            UPDATE users SET fullname=?, email=?, username=?, role=? WHERE id=?
        ");
        $update->execute([$fullname, $email, $username, $role, $id]);
    }

    $mesaj = "Kullanıcı başarıyla güncellendi!";
}

/* --- Kullanıcıları al --- */
$stmt = $db->query("SELECT * FROM users ORDER BY id DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kullanıcılar - Admin Paneli</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
body {
    background:#f1f4f9;
    font-family:'Segoe UI';
}

/* Sidebar */
.sidebar {
    height:100vh;
    background:#0a192f;
    color:white;
    width:230px;
    position:fixed;
    padding:20px;
    top:0;
    overflow-y:auto;
}
.sidebar a {
    color:#dce1eb;
    text-decoration:none;
    padding:10px 0;
    display:block;
}
.sidebar a:hover {
    background:#1b263b;
    padding-left:10px;
    color:#00b4d8;
    transition:.2s;
}

.content {
    margin-left:250px;
    padding:20px;
}

/* Modal */
.modal-content{
    background:#1c1f26;
    color:white;
    border-radius:15px;
    padding:10px;
    box-shadow:0 15px 45px rgba(0,0,0,0.45);
}
.form-control{
    background:#2b2f38;
    border:none;
    color:white;
}
.form-control:focus{
    background:#343945;
    box-shadow:0 0 8px #00b4d8;
}

.btn-edit{
    background:#6c63ff;
    border:none;
}
.btn-edit:hover{
    background:#554dee;
}

.btn-custom{
    background:#00b4d8;
    border:none;
    color:white;
}
.btn-custom:hover{
    background:#0093b3;
}

@media(max-width:768px){
    .sidebar { width:100%; height:auto; position:relative; }
    .content { margin-left:0; }
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <h3 class="mb-4">Admin Paneli</h3>
    <a href="./dashboard.php"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="./haberler.php"><i class="bi bi-newspaper me-2"></i>Haberler</a>
    <a href="./editorler.php"><i class="bi bi-person-badge me-2"></i>Editörler</a>
    <a href="./kategoriler.php"><i class="bi bi-tags me-2"></i>Kategoriler</a>
    <a href="./users.php"><i class="bi bi-people me-2"></i>Kullanıcılar</a>
    <a href="../index.php"><i class="bi bi-house-door me-2"></i>Siteye Git</a>
</div>

<div class="content">

<h2 class="mb-4">Kullanıcılar</h2>

<?php if(!empty($mesaj)): ?>
    <div class="alert alert-success"><?= $mesaj ?></div>
<?php endif; ?>

<!-- ADMİNLER -->
<h4 class="mt-4 text-danger">Adminler</h4>
<div class="table-responsive">
<table class="table table-dark table-striped">
<thead>
<tr>
    <th>#</th><th>Ad Soyad</th><th>Email</th><th>Kullanıcı Adı</th><th>İşlem</th>
</tr>
</thead>
<tbody>
<?php $i=1; foreach($users as $u): if($u['role']=="admin"): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $u['fullname'] ?></td>
    <td><?= $u['email'] ?></td>
    <td><?= $u['username'] ?></td>
    <td>
        <button class="btn btn-edit btn-sm text-white"
            onclick='editUser(<?= json_encode($u) ?>)' data-bs-toggle="modal" data-bs-target="#editModal">
            Düzenle
        </button>
        <a href="?delete_id=<?= $u['id'] ?>" onclick="return confirm('Silinsin mi?')" class="btn btn-danger btn-sm">Sil</a>
    </td>
</tr>
<?php endif; endforeach; ?>
</tbody>
</table>
</div>

<!-- EDİTÖRLER -->
<h4 class="mt-4 text-warning">Editörler</h4>
<div class="table-responsive">
<table class="table table-warning table-striped">
<thead>
<tr>
    <th>#</th><th>Ad Soyad</th><th>Email</th><th>Kullanıcı Adı</th><th>İşlem</th>
</tr>
</thead>
<tbody>
<?php $i=1; foreach($users as $u): if($u['role']=="editor"): ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $u['fullname'] ?></td>
    <td><?= $u['email'] ?></td>
    <td><?= $u['username'] ?></td>
    <td>
        <button class="btn btn-edit btn-sm text-white"
            onclick='editUser(<?= json_encode($u) ?>)' data-bs-toggle="modal" data-bs-target="#editModal">
            Düzenle
        </button>
        <a href="?delete_id=<?= $u['id'] ?>" onclick="return confirm('Silinsin mi?')" class="btn btn-danger btn-sm">Sil</a>
    </td>
</tr>
<?php endif; endforeach; ?>
</tbody>
</table>
</div>

<!-- USER -->
<h4 class="mt-4 text-primary">Normal Kullanıcılar</h4>
<div class="table-responsive">
<table class="table table-striped">
<thead class="table-primary">
<tr>
    <th>#</th><th>Ad Soyad</th><th>Email</th><th>Kullanıcı Adı</th><th>İşlem</th>
</tr>
</thead>
<tbody>
<?php $i=1; foreach($users as $u): if($u['role']=="user" || $u['role']=="kullanici" || $u['role']=="kullanıcı")  :  ?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= $u['fullname'] ?></td>
    <td><?= $u['email'] ?></td>
    <td><?= $u['username'] ?></td>
    <td>
        <button class="btn btn-edit btn-sm text-white"
            onclick='editUser(<?= json_encode($u) ?>)' data-bs-toggle="modal" data-bs-target="#editModal">
            Düzenle
        </button>
        <a href="?delete_id=<?= $u['id'] ?>" onclick="return confirm('Silinsin mi?')" class="btn btn-danger btn-sm">Sil</a>
    </td>
</tr>
<?php endif; endforeach; ?>
</tbody>
</table>
</div>


<!-- Düzenleme Modalı -->
<div class="modal fade" id="editModal">
<div class="modal-dialog">
<form method="POST" class="modal-content">

    <input type="hidden" name="id" id="edit_id">
    <input type="hidden" name="edit_user" value="1">

    <div class="modal-header">
        <h5>Kullanıcı Düzenle</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
    </div>

    <div class="modal-body">

        <label>Ad Soyad</label>
        <input type="text" class="form-control mb-2" id="edit_fullname" name="fullname">

        <label>E-mail</label>
        <input type="email" class="form-control mb-2" id="edit_email" name="email">

        <label>Kullanıcı Adı</label>
        <input type="text" class="form-control mb-2" id="edit_username" name="username">

        <label>Yeni Şifre (Boş ise değişmez)</label>
        <input type="password" class="form-control mb-2" name="password">

        <label>Rol Seç</label>
        <select class="form-control" name="role" id="edit_role">
            <option value="user">User</option>
            <option value="editor">Editor</option>
            <option value="admin">Admin</option>
        </select>

    </div>

    <div class="modal-footer">
        <button class="btn btn-custom">Kaydet</button>
    </div>

</form>
</div>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editUser(u){
    document.getElementById("edit_id").value = u.id;
    document.getElementById("edit_fullname").value = u.fullname;
    document.getElementById("edit_email").value = u.email;
    document.getElementById("edit_username").value = u.username;
    document.getElementById("edit_role").value = u.role;
}
</script>

</body>
</html>
