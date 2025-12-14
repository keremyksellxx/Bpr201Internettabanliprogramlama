<?php
/**
 * Veritabanı Bağlantı Modülü - Gelişmiş Güvenlik
 * 
 * PDO kullanarak güvenli ve optimize edilmiş veritabanı bağlantısı sağlar
 * - Prepared statements ile SQL injection koruması
 * - Merkezi konfigürasyon sistemi
 * - Hata yönetimi ve loglama
 * - Connection pooling desteği
 * 
 * @author BPRFINAL Team - Database Security Module  
 * @version 2.0
 * @since 2025-12-14
 */

// Konfigürasyon dosyasını yükle - merkezi ayarlar
require_once __DIR__ . '/includes/config.php';

// Global veritabanı bağlantı nesnesi
$db = null;

try {
    // DSN (Data Source Name) oluştur - güvenli bağlantı stringi
    $dsn = sprintf(
        "mysql:host=%s;dbname=%s;charset=%s",
        DB_HOST,
        DB_NAME,
        DB_CHARSET
    );
    
    // PDO bağlantısı oluştur - güvenlik parametreleriyle
    $db = new PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
    
    // Bağlantı başarılı - debug modunda logla
    if (isDebugMode()) {
        error_log("[DB] Veritabanı bağlantısı başarılı: " . DB_NAME);
    }
    
} catch (PDOException $e) {
    // Bağlantı hatası - güvenlik için detayları gizle
    
    // Hata logla - sistem yöneticisi için
    $error_message = sprintf(
        "[DB ERROR] %s - Code: %s - File: %s - Line: %d",
        $e->getMessage(),
        $e->getCode(),
        $e->getFile(),
        $e->getLine()
    );
    error_log($error_message);
    
    // Production'da genel hata mesajı göster - güvenlik
    if (isProductionMode()) {
        die("Veritabanı bağlantı hatası oluştu. Lütfen daha sonra tekrar deneyin.");
    } else {
        // Development'ta detaylı hata göster - debug için
        die("<h3>Veritabanı Bağlantı Hatası</h3>
             <p><strong>Hata:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
             <p><strong>Kod:</strong> " . $e->getCode() . "</p>
             <p><strong>Dosya:</strong> " . $e->getFile() . " (Satır: " . $e->getLine() . ")</p>
             <hr>
             <p><em>Not: Production modunda bu detaylar gösterilmeyecektir.</em></p>");
    }
}

/**
 * Veritabanı Bağlantısını Kapat
 * 
 * Script sonunda otomatik çağrılır
 * Resource'ları temizler ve bağlantıyı düzgün kapatır
 */
function closeDatabase() {
    global $db;
    
    if ($db !== null) {
        $db = null; // PDO bağlantısını kapat
        
        if (isDebugMode()) {
            error_log("[DB] Veritabanı bağlantısı kapatıldı");
        }
    }
}

// Script sonunda bağlantıyı kapat - resource yönetimi
register_shutdown_function('closeDatabase');

?>
