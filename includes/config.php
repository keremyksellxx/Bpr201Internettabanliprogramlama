<?php
/**
 * Konfigurasyon Dosyası - Merkezi Ayarlar
 * 
 * Tüm proje ayarlarını merkezi bir yerden yönetir
 * Güvenlik, veritabanı, mail ve genel ayarlar
 * 
 * ÖNEMLII: Bu dosyayı asla public erişime açmayın!
 * .gitignore dosyasına ekleyin ve production'da farklı ayarlar kullanın
 * 
 * @author BPRFINAL Team
 * @version 2.0
 */

// =============================================================================
// GENEL AYARLAR
// =============================================================================

// Uygulama modunu belirle - PRODUCTION'da mutlaka 'production' olmalı!
define('APP_ENV', 'development'); // development veya production

// Hata raporlama ayarları - guvenlik acısından kritk
if (APP_ENV === 'production') {
    // Production modunda hataları gosterme - güvenlik riski
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(E_ALL);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
} else {
    // Development modunda hataları göstr - gelistirme kolaylığı
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

// Zaman dilimi ayarı - Turkiye saati
date_default_timezone_set('Europe/Istanbul');

// Karakter kodlaması - UTF-8 zorunlu
ini_set('default_charset', 'UTF-8');
mb_internal_encoding('UTF-8');

// =============================================================================
// VERİTABANI AYARLARI - GÜVENLİK ÖNEMLİ!
// =============================================================================

/**
 * PRODUCTION UYARI:
 * Bu bilgileri asla kodda saklamayın!
 * Environment variable kullanın veya güvenli konfig dosyası kullanın
 * 
 * Örnek production kullanımı:
 * define('DB_HOST', getenv('DB_HOST'));
 * define('DB_NAME', getenv('DB_NAME'));
 * define('DB_USER', getenv('DB_USER'));
 * define('DB_PASS', getenv('DB_PASS'));
 */

// Development ayarları - PRODUCTION'DA DEĞİŞTİRİN!
define('DB_HOST', 'localhost');
define('DB_NAME', 'haber_portali');
define('DB_USER', 'root');
define('DB_PASS', ''); // PRODUCTION'da guclu sifre kullanın!
define('DB_CHARSET', 'utf8mb4');

// PDO seçenekleri - güvenlik ve performans için optimize edilmış
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,  // Hata modunu exception yap
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,        // Varsayılan fetch modu
    PDO::ATTR_EMULATE_PREPARES   => false,                   // Gerçek prepared statements kullan
    PDO::ATTR_PERSISTENT         => false,                   // Kalıcı bağlantı kullanma
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"     // UTF-8 karakter seti
]);

// =============================================================================
// GÜVENLİK AYARLARI
// =============================================================================

// CSRF Token süresi (saniye cinsinden)
define('CSRF_TOKEN_LIFETIME', 3600); // 1 saat

// Session timeout süresi (saniye cinsinden)
define('SESSION_LIFETIME', 1800); // 30 dakika

// Rate limiting ayarları
define('RATE_LIMIT_LOGIN_ATTEMPTS', 5);      // Maksimum login denemesi
define('RATE_LIMIT_LOGIN_WINDOW', 900);      // 15 dakika içinde
define('RATE_LIMIT_REGISTER_ATTEMPTS', 3);   // Maksimum kayıt denemesi
define('RATE_LIMIT_REGISTER_WINDOW', 3600);  // 1 saat içinde

// Şifre gereksinimleri
define('PASSWORD_MIN_LENGTH', 8);
define('PASSWORD_REQUIRE_UPPERCASE', true);
define('PASSWORD_REQUIRE_LOWERCASE', true);
define('PASSWORD_REQUIRE_NUMBER', true);
define('PASSWORD_REQUIRE_SPECIAL', true);

// Maksimum dosya yükleme boyutu (byte cinsinden)
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB

// İzin verilen dosya tipleri - guvenlik icin kısıtlı
define('ALLOWED_IMAGE_TYPES', [
    'image/jpeg' => 'jpg',
    'image/pjpeg' => 'jpg',
    'image/png' => 'png',
    'image/gif' => 'gif'
]);

// =============================================================================
// UYGULAMA AYARLARI
// =============================================================================

// Site bilgileri
define('SITE_NAME', 'KYHABER');
define('SITE_TAGLINE', 'Gündemi Bizimle Takip Et');
define('SITE_URL', 'http://localhost/Bpr201Internettabanliprogramlama');

// Sayfalama ayarları
define('ITEMS_PER_PAGE', 12);
define('MAX_PAGINATION_LINKS', 5);

// Upload klasörü yolu
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', 'uploads/');

// Log klasörü yolu
define('LOG_DIR', __DIR__ . '/../logs/');

// =============================================================================
// EMAIL AYARLARI (İsteğe Bağlı)
// =============================================================================

// SMTP ayarları - şifre sıfırlama vb için kullanılabilir
define('SMTP_ENABLED', false); // Email gonderimi aktif mi?
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-email@gmail.com');
define('SMTP_PASSWORD', 'your-app-password'); // PRODUCTION'da güvenli şekilde saklyın!
define('SMTP_FROM_EMAIL', 'noreply@kyhaber.com');
define('SMTP_FROM_NAME', 'KYHABER');

// =============================================================================
// API ANAHTARLARI (İsteğe Bağlı)
// =============================================================================

// Harici servisler için API anahtarları
// PRODUCTION'da environment variable olarak saklanmalı!
define('RECAPTCHA_SITE_KEY', '');
define('RECAPTCHA_SECRET_KEY', '');

// =============================================================================
// CACHE AYARLARI
// =============================================================================

// Cache süresi (saniye cinsinden)
define('CACHE_ENABLED', false); // Cache aktif mi?
define('CACHE_LIFETIME', 3600); // 1 saat

// =============================================================================
// DEBUGGING AYARLARI
// =============================================================================

// SQL query loglama - sadece developmentda
define('LOG_SQL_QUERIES', APP_ENV === 'development');

// Güvenlik olaylarını logla
define('LOG_SECURITY_EVENTS', true);

// =============================================================================
// YARDIMCI FONKSİYONLAR
// =============================================================================

/**
 * Konfigürasyon Değerini Al
 * 
 * Belirtilen anahtarın degerini dondurur
 * Tanımlı değilse varsayılan değeri döndürür
 * 
 * @param string $key Anahtar adı
 * @param mixed $default Varsayılan dğer
 * @return mixed
 */
function config($key, $default = null) {
    if (defined($key)) {
        return constant($key);
    }
    return $default;
}

/**
 * Debug Modu Aktif Mi?
 * 
 * Development modunda debug mesajları göster
 * 
 * @return bool
 */
function isDebugMode() {
    return APP_ENV === 'development';
}

/**
 * Production Modu Aktif Mi?
 * 
 * Production modunda ekstra güvenlik önlemleri
 * 
 * @return bool
 */
function isProductionMode() {
    return APP_ENV === 'production';
}

/**
 * Log Dizini Oluştur
 * 
 * Log klasörü yoksa oluşturur
 */
function ensureLogDirectory() {
    if (!is_dir(LOG_DIR)) {
        @mkdir(LOG_DIR, 0755, true);
        
        // .htaccess ile erişimi engelle - guvenlik
        $htaccess = LOG_DIR . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess, "Deny from all\n");
        }
    }
}

/**
 * Upload Dizini Oluştur
 * 
 * Upload klasörü yoksa oluşturur
 */
function ensureUploadDirectory() {
    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0755, true);
        
        // .htaccess ile PHP çalıştırmayı engelle - güvenlik
        $htaccess = UPLOAD_DIR . '.htaccess';
        if (!file_exists($htaccess)) {
            $content = "# Dosya yüklemelerinde PHP çalıştırmayı engelle\n";
            $content .= "php_flag engine off\n";
            $content .= "AddType text/plain .php .php3 .php4 .php5 .phtml\n";
            file_put_contents($htaccess, $content);
        }
    }
}

// Gerekli klasörleri oluştur
ensureLogDirectory();
ensureUploadDirectory();

// =============================================================================
// AUTOLOAD - Gerekli dosyaları otomatik yükle
// =============================================================================

/**
 * Kritik dosyaları yükle
 * Güvenlik ve validasyon fonksiyonları her zaman hazır olmalı
 */
function autoloadIncludes() {
    $includes_dir = __DIR__ . '/';
    
    // Önce güvenlik dosyasını yükle
    if (file_exists($includes_dir . 'security.php')) {
        require_once $includes_dir . 'security.php';
    }
    
    // Sonra validasyon dosyasını yükle
    if (file_exists($includes_dir . 'validation.php')) {
        require_once $includes_dir . 'validation.php';
    }
}

// Otomatik yükleme başlat
autoloadIncludes();

?>
