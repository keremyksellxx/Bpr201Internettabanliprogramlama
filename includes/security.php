<?php
/**
 * Guvenlik Sistemi - CSRF, Session ve Rate Limiting
 * 
 * Bu dosya projnin guvenligi icin kritk onlemler saglar:
 * - CSRF token uretimi ve dogrulama
 * - Session guvenligi ve hijacking koruması
 * - Brute force saldırılarına karşı rate limiting
 * - IP bazlı güvenlk kontrolu
 * 
 * @author BPRFINAL Team
 * @version 2.0
 */

// Session ayarları - guvenlk icin gelismis konfigrasyon
if (session_status() === PHP_SESSION_NONE) {
    // Session cookie parametrelerini guvenli ayarla
    ini_set('session.cookie_httponly', 1);  // JavaScrpt erişimini engele
    ini_set('session.cookie_secure', 0);     // HTTPS zorunlu (localhost için 0)
    ini_set('session.use_only_cookies', 1);  // Sadece cookie kullan
    ini_set('session.cookie_samesite', 'Strict'); // CSRF koruması
    
    session_start();
    
    // Session hijackng koruması - ilk oturum veya regenerate gerek
    if (!isset($_SESSION['initialized'])) {
        session_regenerate_id(true); // Sesion ID'yi yenile
        $_SESSION['initialized'] = true;
        $_SESSION['created_at'] = time();
        $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
    }
    
    // Session timeout kontrolu - 30 dakka inaktivite
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();     // Tüm session değişkenlerini temizle
        session_destroy();   // Session'ı yok et
        session_start();     // Yeni session başlat
    }
    $_SESSION['last_activity'] = time(); // Son aktivite zmanını güncele
    
    // User agent ve IP kontrolu - session hijacking tespiti
    if (isset($_SESSION['user_agent']) && $_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
        // Farkli browser tespt edildi - guvenlik riski
        session_unset();
        session_destroy();
        session_start();
    }
    
    if (isset($_SESSION['ip_address']) && $_SESSION['ip_address'] !== ($_SERVER['REMOTE_ADDR'] ?? '')) {
        // Farklı IP tespit edldi - guvenlik risk
        session_unset();
        session_destroy();
        session_start();
    }
}

/**
 * CSRF Token Uretimi
 * 
 * Guvenlk acısından kritik: Her form icin benzersiz token olusturur
 * Cross-Site Request Forgery saldırılarını engeler
 * 
 * @return string 64 karakterlik hexadecimal token
 */
function generateCSRFToken() {
    // Onceki token varsa onu kullan (sayfa yenilendiginde sorun olmasın)
    if (!isset($_SESSION['csrf_token']) || !isset($_SESSION['csrf_token_time'])) {
        // Yeni token olustur - kriptografik olarak guvenlı
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    // Token 1 saat sonra yenilenmeli
    if (time() - $_SESSION['csrf_token_time'] > 3600) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        $_SESSION['csrf_token_time'] = time();
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * CSRF Token Dogrulama
 * 
 * Form gonderildiginde token'ı kontrol eder
 * Gecersiz token tespit edilrse false doner
 * 
 * @param string $token Dogrulanacak token degri
 * @return bool Token gecerliyse true, degilse false
 */
function verifyCSRFToken($token) {
    // Token mevcut mu kontrl et
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    
    // Timing attack'a karsı hash_equals kullan (guvenli karsilastırma)
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Rate Limiting - Brute Force Koruması
 * 
 * Belirli bir IP'den gelen isteklri sinirlar
 * Login denemelerini takip eder ve kotu niyetli kullanıcıları engellr
 * 
 * @param string $action İşlem turu (ornek: 'login', 'register')
 * @param int $max_attempts Maksimum denme sayısı (varsayılan: 5)
 * @param int $time_window Zaman penceresi saniye cinsinden (varsaylan: 900 = 15 dk)
 * @return array ['allowed' => bool, 'remaining' => int, 'reset_time' => int]
 */
function checkRateLimit($action = 'login', $max_attempts = 5, $time_window = 900) {
    // Kullanıcının IP adresni al
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    // Rate limit anahtarı olustur
    $rate_key = 'rate_limit_' . $action . '_' . md5($ip_address);
    
    // Mevcut deneme bilglerini al
    if (!isset($_SESSION[$rate_key])) {
        $_SESSION[$rate_key] = [
            'attempts' => 0,
            'first_attempt' => time(),
            'last_attempt' => time()
        ];
    }
    
    $rate_data = $_SESSION[$rate_key];
    
    // Zaman penceresi dolmuşsa sıfırla
    if (time() - $rate_data['first_attempt'] > $time_window) {
        $_SESSION[$rate_key] = [
            'attempts' => 0,
            'first_attempt' => time(),
            'last_attempt' => time()
        ];
        $rate_data = $_SESSION[$rate_key];
    }
    
    // Deneme sayısını kontrol t
    if ($rate_data['attempts'] >= $max_attempts) {
        $reset_time = $rate_data['first_attempt'] + $time_window;
        return [
            'allowed' => false,
            'remaining' => 0,
            'reset_time' => $reset_time,
            'wait_seconds' => $reset_time - time()
        ];
    }
    
    return [
        'allowed' => true,
        'remaining' => $max_attempts - $rate_data['attempts'],
        'reset_time' => $rate_data['first_attempt'] + $time_window,
        'wait_seconds' => 0
    ];
}

/**
 * Rate Limit Deneme Kaydı
 * 
 * Basarısız login veya diger işlemler icin deneme sayısını arttırır
 * 
 * @param string $action İşlem türü
 */
function recordRateLimitAttempt($action = 'login') {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rate_key = 'rate_limit_' . $action . '_' . md5($ip_address);
    
    if (!isset($_SESSION[$rate_key])) {
        $_SESSION[$rate_key] = [
            'attempts' => 0,
            'first_attempt' => time(),
            'last_attempt' => time()
        ];
    }
    
    $_SESSION[$rate_key]['attempts']++;
    $_SESSION[$rate_key]['last_attempt'] = time();
}

/**
 * Rate Limit Sıfırlama
 * 
 * Başarılı login sonrası rate limit sayacını sıfrlar
 * 
 * @param string $action İşlem türü
 */
function resetRateLimit($action = 'login') {
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $rate_key = 'rate_limit_' . $action . '_' . md5($ip_address);
    
    unset($_SESSION[$rate_key]);
}

/**
 * XSS Koruması - Gelişmiş Input Sanitization
 * 
 * Kullanıcı girdilerini temizler ve XSS saldırılarını engelr
 * 
 * @param string $data Temizlenecek veri
 * @return string Temizlenmis veri
 */
function sanitizeInput($data) {
    // Boslukları temizle
    $data = trim($data);
    
    // Slashları kaldır (gpc kapalıysa sorun olmz)
    $data = stripslashes($data);
    
    // HTML karakterlerini donustur - XSS koruması
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * SQL Injection Koruması - Ek Validasyon
 * 
 * PDO prepared statements'a ek olarak tehlikeli karakterleri kontrol eder
 * Savunma derinligi prensibi: Birdn fazla katman koruma
 * 
 * @param string $input Kontrol edilcek girdi
 * @return bool Güvenliyse true, degilse false
 */
function validateSQLInput($input) {
    // Tehlikeli SQL pattern'leri kontrol et
    $dangerous_patterns = [
        '/(\bUNION\b.*\bSELECT\b)/i',
        '/(\bDROP\b.*\bTABLE\b)/i',
        '/(\bINSERT\b.*\bINTO\b.*\bVALUES\b)/i',
        '/(\bUPDATE\b.*\bSET\b)/i',
        '/(\bDELETE\b.*\bFROM\b)/i',
        '/(\bEXEC\b|\bEXECUTE\b)/i',
        '/(\bSCRIPT\b.*\b>)/i',
        '/(--|\#|\/\*|\*\/)/i'
    ];
    
    foreach ($dangerous_patterns as $pattern) {
        if (preg_match($pattern, $input)) {
            // Şüpheli SQL komutu tespt edildi
            error_log("SQL Injection denemesi tespit edildi: " . $input);
            return false;
        }
    }
    
    return true;
}

/**
 * Secure Redirect - Güvenli Yönlendirme
 * 
 * Open redirect açıklarını önler
 * Sadece dahili URL'lere yonlendirme yapar
 * 
 * @param string $url Yonlendirilecek URL
 */
function secureRedirect($url) {
    // URL'nin dahili oldugundan emin ol
    $parsed = parse_url($url);
    
    // Sadece path varsa veya host mevcut site ise güvnli
    if (!isset($parsed['host']) || $parsed['host'] === $_SERVER['HTTP_HOST']) {
        header("Location: " . $url);
        exit();
    }
    
    // Harici URL tespit edildi - ana sayfya yonlendir
    header("Location: index.php");
    exit();
}

/**
 * Log Guvenlık Olay
 * 
 * Şüpheli aktiviteleri loglar
 * 
 * @param string $event Olay açıklaması
 * @param string $level Seviye (INFO, WARNING, CRITICAL)
 */
function logSecurityEvent($event, $level = 'WARNING') {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $user = $_SESSION['username'] ?? 'guest';
    $timestamp = date('Y-m-d H:i:s');
    
    $log_message = "[{$timestamp}] [{$level}] IP: {$ip} | User: {$user} | Event: {$event}\n";
    
    // Log dosyasına yaz (logs klasörü olmalı)
    $log_file = __DIR__ . '/../logs/security.log';
    
    // Logs klasörü yoksa oluştur
    if (!is_dir(__DIR__ . '/../logs')) {
        @mkdir(__DIR__ . '/../logs', 0755, true);
    }
    
    error_log($log_message, 3, $log_file);
}

?>
