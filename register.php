<?php
/**
 * Kullanıcı Kayıt Sayfası - Gelişmiş Güvenlik Modülü
 * 
 * Kullanıcı kaydı için gelişmiş güvenlik önlemleri:
 * - CSRF token koruması
 * - Rate limiting (brute force önlemi)
 * - Gelişmiş şifre validasyonu (min 8 kar, büyük/küçük harf, özel karakter)
 * - Email ve username kontrolü
 * - SQL injection koruması
 * - XSS koruması
 * 
 * @author BPRFINAL Team - Öğrenci No: 221229034, 221229056
 * @version 2.0
 * @since 2025-12-14
 */

require_once "db.php";

$error = "";
$success = "";
$password_strength = "";

// Form gönderilmişse - POST metodu kontrolü
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    // CSRF token doğrulama - güvenlik katmanı 1
    $csrf_token = $_POST['csrf_token'] ?? '';
    if (!verifyCSRFToken($csrf_token)) {
        $error = "Güvenlik doğrulaması başarısız. Lütfen sayfayı yenileyin ve tekrar deneyin.";
        logSecurityEvent("CSRF token başarısız - Register", "WARNING");
    } else {
        
        // Rate limiting kontrolü - brute force koruması
        $rate_check = checkRateLimit('register', RATE_LIMIT_REGISTER_ATTEMPTS, RATE_LIMIT_REGISTER_WINDOW);
        
        if (!$rate_check['allowed']) {
            $wait_minutes = ceil($rate_check['wait_seconds'] / 60);
            $error = "Çok fazla kayıt denemesi yaptınız. Lütfen {$wait_minutes} dakika sonra tekrar deneyin.";
            logSecurityEvent("Rate limit aşıldı - Register attempt", "WARNING");
        } else {
            
            // Form verilerini al ve temizle - XSS koruması
            $fullname = sanitizeInput(trim($_POST["fullname"] ?? ""));
            $email    = sanitizeInput(trim($_POST["email"] ?? ""));
            $username = sanitizeInput(trim($_POST["username"] ?? ""));
            $password = $_POST["password"] ?? ""; // Şifre sanitize edilmez (hash'lenecek)
            
            // Boş alan kontrolü - temel validasyon
            if ($fullname === "" || $email === "" || $username === "" || $password === "") {
                $error = "Lütfen tüm alanları doldurun.";
                recordRateLimitAttempt('register');
            }
            // Tam ad validasyonu - gelişmiş kontrol
            else {
                $fullname_check = validateFullname($fullname);
                if (!$fullname_check['valid']) {
                    $error = $fullname_check['error'];
                    recordRateLimitAttempt('register');
                }
                // Email validasyonu - RFC uyumlu
                else {
                    $email_check = validateEmail($email);
                    if (!$email_check['valid']) {
                        $error = $email_check['error'];
                        recordRateLimitAttempt('register');
                    }
                    // Kullanıcı adı validasyonu - güvenlik kuralları
                    else {
                        $username_check = validateUsername($username);
                        if (!$username_check['valid']) {
                            $error = $username_check['error'];
                            recordRateLimitAttempt('register');
                        }
                        // Şifre gücü kontrolü - gelişmiş standartlar
                        else {
                            $password_check = validatePassword($password);
                            if (!$password_check['valid']) {
                                $error = "Şifre gereksinimleri:<br>" . implode("<br>", $password_check['errors']);
                                $password_strength = $password_check['strength'];
                                recordRateLimitAttempt('register');
                            }
                            // Tüm validasyonlar başarılı - veritabanı işlemleri
                            else {
                                try {
                                    
                                    // SQL injection ek kontrol - savunma derinliği
                                    if (!validateSQLInput($email) || !validateSQLInput($username)) {
                                        $error = "Geçersiz karakter tespit edildi. Lütfen kontrol edin.";
                                        logSecurityEvent("SQL injection denemesi tespit edildi - Register", "CRITICAL");
                                        recordRateLimitAttempt('register');
                                    } else {
                                        
                                        // Aynı kullanıcı veya email var mı kontrol - prepared statement
                                        $check = $db->prepare("SELECT id FROM users WHERE email = ? OR username = ? LIMIT 1");
                                        $check->execute([$email, $username]);

                                        if ($check->rowCount() > 0) {
                                            $error = "Bu e-posta veya kullanıcı adı zaten kullanılıyor.";
                                            recordRateLimitAttempt('register');
                                        } 
                                        else {
                                            
                                            // Şifreyi hash'le - güvenli saklama (bcrypt)
                                            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                                            $role = "user"; // Varsayılan rol - güvenlik için

                                            // Kullanıcı kaydet - prepared statement ile SQL injection koruması
                                            $insert = $db->prepare("
                                                INSERT INTO users (fullname, email, username, password, role, created_at)
                                                VALUES (?, ?, ?, ?, ?, NOW())
                                            ");

                                            $result = $insert->execute([
                                                $fullname,
                                                $email,
                                                $username,
                                                $hashedPassword,
                                                $role
                                            ]);

                                            if ($result) {
                                                // Kayıt başarılı - rate limit sıfırla
                                                resetRateLimit('register');
                                                
                                                // Güvenlik olayını logla
                                                logSecurityEvent("Yeni kullanıcı kaydı: " . $username, "INFO");
                                                
                                                // Login sayfasına yönlendir
                                                header("Location: login.php?msg=success");
                                                exit();
                                            } else {
                                                $error = "Kayıt sırasında hata oluştu.";
                                                recordRateLimitAttempt('register');
                                            }
                                        }
                                    }
                                } 
                                catch (PDOException $e) {
                                    // Veritabanı hatası - logla ve genel mesaj göster
                                    error_log("REGISTER ERROR: " . $e->getMessage());
                                    logSecurityEvent("Database error - Register: " . $e->getMessage(), "CRITICAL");
                                    
                                    // Production'da detay gösterme
                                    if (isProductionMode()) {
                                        $error = "Sunucu hatası oluştu. Lütfen daha sonra tekrar deneyin.";
                                    } else {
                                        $error = "Sunucu hatası: " . $e->getMessage();
                                    }
                                    
                                    recordRateLimitAttempt('register');
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

// CSRF token oluştur - form için
$csrf_token = generateCSRFToken();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kayıt Ol | KYHABER Haber Portalı</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        background-color: #0a192f;
        font-family: 'Segoe UI', sans-serif;
    }

    ::placeholder {
        color: #eee !important;
        opacity: 1 !important;
    }

    .register-box {
        background-color: #112240;
        border-radius: 16px;
        padding: 40px;
        margin-top: 100px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.35);
        animation: slideDown 0.7s ease both;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-40px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    h3, p {
        color: #dce1eb;
    }

    .form-control {
        background-color: #0a192f !important;
        border: 1px solid #233554;
        color: white !important;
        height: 48px;
    }

    .form-control:focus {
        background-color: #0a192f !important;
        color: #fff !important;
        border-color: #64ffda;
        box-shadow: 0 0 10px #64ffda55;
    }

    /* Chrome autofill (beyaz arka plan) düzeltme */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus {
        -webkit-box-shadow: 0 0 0 1000px #0a192f inset !important;
        -webkit-text-fill-color: #fff !important;
        transition: background-color 9999s ease-in-out 0s;
    }

    .btn-custom {
        background-color: #64ffda;
        color: #0a192f;
        font-weight: bold;
        transition: 0.3s;
        height: 48px;
        border-radius: 8px;
    }

    .btn-custom:hover {
        background-color: #48d9b7;
        transform: translateY(-2px);
    }

    a { color: #64ffda; }
    
    /* Şifre gücü göstergesi */
    .password-strength {
        height: 5px;
        margin-top: 5px;
        border-radius: 3px;
        transition: all 0.3s;
    }
    .strength-weak { background-color: #ff4444; width: 33%; }
    .strength-medium { background-color: #ffbb33; width: 66%; }
    .strength-strong { background-color: #00C851; width: 100%; }
    
    .password-requirements {
        font-size: 0.85rem;
        color: #a8b2d1;
        margin-top: 10px;
    }
    .requirement-met { color: #64ffda; }
    .requirement-unmet { color: #ff6b6b; }
</style>

</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="register-box">

                <h3 class="text-center mb-4">📝 Kayıt Ol</h3>

                <?php if(!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                
                <?php if(!empty($success)): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST" id="registerForm">
                    
                    <!-- CSRF Token - güvenlik -->
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">

                    <div class="mb-3">
                        <input type="text" class="form-control" name="fullname" id="fullname"
                        placeholder="Ad Soyad" required
                        value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                        <small class="text-muted">Sadece harf ve boşluk içermelidir</small>
                    </div>

                    <div class="mb-3">
                        <input type="email" class="form-control" name="email" id="email"
                        placeholder="E-Posta" required
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                        <small class="text-muted">Geçerli bir email adresi girin</small>
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" name="username" id="username"
                        placeholder="Kullanıcı Adı" required
                        value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                        <small class="text-muted">3-30 karakter, sadece harf, rakam, _ ve -</small>
                    </div>

                    <div class="mb-3">
                        <input type="password" class="form-control" name="password" id="password"
                        placeholder="Şifre" required>
                        <div class="password-strength" id="passwordStrength"></div>
                        <div class="password-requirements">
                            <small>Şifre gereksinimleri:</small><br>
                            <small id="req-length" class="requirement-unmet">✗ En az 8 karakter</small><br>
                            <small id="req-upper" class="requirement-unmet">✗ En az 1 büyük harf (A-Z)</small><br>
                            <small id="req-lower" class="requirement-unmet">✗ En az 1 küçük harf (a-z)</small><br>
                            <small id="req-number" class="requirement-unmet">✗ En az 1 rakam (0-9)</small><br>
                            <small id="req-special" class="requirement-unmet">✗ En az 1 özel karakter (!@#$%^&*)</small>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-custom w-100">Kayıt Ol</button>
                </form>

                <p class="text-center mt-3 small">
                    Zaten hesabın var mı? <a href="login.php">Giriş yap</a>
                </p>

            </div>
        </div>
    </div>
</div>

<script>
// Şifre gücü kontrolü - gerçek zamanlı
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthBar = document.getElementById('passwordStrength');
    
    // Gereksinimleri kontrol et
    const hasLength = password.length >= 8;
    const hasUpper = /[A-Z]/.test(password);
    const hasLower = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecial = /[!@#$%^&*()_+\-=\[\]{};:'",.<>?\/\\|`~]/.test(password);
    
    // Görsel güncelleme
    updateRequirement('req-length', hasLength);
    updateRequirement('req-upper', hasUpper);
    updateRequirement('req-lower', hasLower);
    updateRequirement('req-number', hasNumber);
    updateRequirement('req-special', hasSpecial);
    
    // Güç skoru hesapla
    let score = 0;
    if (hasLength) score++;
    if (hasUpper) score++;
    if (hasLower) score++;
    if (hasNumber) score++;
    if (hasSpecial) score++;
    
    // Güç çubuğunu güncelle
    strengthBar.className = 'password-strength';
    if (score >= 5) {
        strengthBar.classList.add('strength-strong');
    } else if (score >= 3) {
        strengthBar.classList.add('strength-medium');
    } else if (score > 0) {
        strengthBar.classList.add('strength-weak');
    }
});

function updateRequirement(id, met) {
    const elem = document.getElementById(id);
    if (met) {
        elem.className = 'requirement-met';
        elem.textContent = elem.textContent.replace('✗', '✓');
    } else {
        elem.className = 'requirement-unmet';
        elem.textContent = elem.textContent.replace('✓', '✗');
    }
}
</script>

</body>
</html>
