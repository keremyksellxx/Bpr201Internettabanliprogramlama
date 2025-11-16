<?php
require_once "db.php";

$admin_password = password_hash("admin123", PASSWORD_DEFAULT);

$stmt = $db->prepare("UPDATE users SET password=?, role='admin' WHERE username='admin'");
$stmt->execute([$admin_password]);

echo "Admin şifresi güncellendi!";
?>

<?php
require_once "db.php"; // PDO bağlantısı

// Örnek editör ekleme
$fullname = "Kerem Yüksel";
$username = "kerem";
$email = "kerem@gmail.com";
$password = password_hash("editor123", PASSWORD_DEFAULT); // Şifre hashleniyor
$role = "editor";

$stmt = $db->prepare("INSERT INTO users (fullname, username, email, password, role) VALUES (?,?,?,?,?)");
$stmt->execute([$fullname, $username, $email, $password, $role]);

echo "Editör başarıyla eklendi!";
?>
