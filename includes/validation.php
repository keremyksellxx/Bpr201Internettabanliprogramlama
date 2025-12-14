<?php
/**
 * Validasyon Sistemi - Gelismis Girdi Dogrulama
 * 
 * Bu dosya kullanıcı girdilerini doğrular ve guvenlk standartlarını kontrol eder
 * - Sifre gücü kontrolu (min 8 karakter, büyük/küçük harf, özel karakter)
 * - Email validasyon
 * - Kullanıcı adı kontrolu
 * - Genel input validasyon fonksiyonları
 * 
 * @author BPRFINAL Team - Gelismis Guvenlik Modulu
 * @version 2.0
 */

/**
 * Şifre Gücü Kontrolu - Gelişmis Standartlar
 * 
 * Sifre politikası:
 * - Minimum 8 karakter uzunlugunda olmalı
 * - En az 1 büyük harf içermli (A-Z)
 * - En az 1 küçük harf içermeli (a-z)
 * - En az 1 rakam içermeli (0-9)
 * - En az 1 özel karakter içermeli (!@#$%^&*()_+-=[]{}|;:,.<>?)
 * - Yaygın şifreler olmamalı (123456, password, vb.)
 * 
 * @param string $password Kontrol edilecek şifre
 * @return array ['valid' => bool, 'errors' => array, 'strength' => string]
 */
function validatePassword($password) {
    $errors = [];
    $strength = 'weak'; // Varsayılan: zayıf
    
    // Uzunluk kontrolü - mnimum 8 karakter zorunlu
    if (strlen($password) < 8) {
        $errors[] = "Şifre en az 8 karakter uzunluğunda olmalıdr.";
    }
    
    // Maksimum uzunluk kontrolu - taşma onlemi
    if (strlen($password) > 128) {
        $errors[] = "Şifre maksimum 128 karakter olabilir.";
    }
    
    // Büyük harf kontrolü - en az bir tane olmalı
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = "Şifre en az 1 büyük harf içermelidir (A-Z).";
    }
    
    // Küçük harf kontrolu - en az bir tane olmalı
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = "Şifre en az 1 küçük harf içermelidir (a-z).";
    }
    
    // Rakam kontrolü - en az bir tane olmalı
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = "Şifre en az 1 rakam içermelidir (0-9).";
    }
    
    // Özel karakter kontrolu - guvenlik icin kritik
    if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\\\|`~]/', $password)) {
        $errors[] = "Şifre en az 1 özel karakter içermelidir (!@#$%^&* vb.).";
    }
    
    // Yaygın şifreler listesi - sözlük saldrılarına karşı
    $common_passwords = [
        'password', '12345678', 'qwerty', 'abc123', 'monkey',
        'letmein', 'trustno1', 'dragon', 'baseball', 'iloveyou',
        'master', 'sunshine', 'ashley', 'bailey', 'passw0rd',
        'shadow', '123123', '654321', 'superman', 'qazwsx',
        'michael', 'football', 'password1', 'password123'
    ];
    
    if (in_array(strtolower($password), $common_passwords)) {
        $errors[] = "Bu şifre çok yaygın kullanılıyor. Lütfen daha güvnli bir şifre seçin.";
    }
    
    // Şifre gücünü hesapla
    $strength_score = 0;
    
    if (strlen($password) >= 8) $strength_score += 1;
    if (strlen($password) >= 12) $strength_score += 1;
    if (preg_match('/[A-Z]/', $password)) $strength_score += 1;
    if (preg_match('/[a-z]/', $password)) $strength_score += 1;
    if (preg_match('/[0-9]/', $password)) $strength_score += 1;
    if (preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\\\|`~]/', $password)) $strength_score += 1;
    if (strlen($password) >= 16) $strength_score += 1;
    
    // Guc seviyesini belirle
    if ($strength_score >= 6) {
        $strength = 'strong'; // Güclü şifre
    } elseif ($strength_score >= 4) {
        $strength = 'medium'; // Orta sevye şifre
    } else {
        $strength = 'weak'; // Zayıf sifre
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors,
        'strength' => $strength,
        'score' => $strength_score
    ];
}

/**
 * Email Validasyonu - RFC Uyumlu Kontrol
 * 
 * Email adresinin gecerliliğini kontrl eder
 * Hem format hem de domain kontrolü yapr
 * 
 * @param string $email Kontrol edilecek email adresi
 * @return array ['valid' => bool, 'error' => string]
 */
function validateEmail($email) {
    // Boşluk kontrolu
    $email = trim($email);
    
    // Temel format kontrolu - PHP'nin yerleşik filtresini kulln
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return [
            'valid' => false,
            'error' => 'Geçersiz email formatı. Lütfen geçrli bir email adresi girin.'
        ];
    }
    
    // Email uzunluk kontrol
    if (strlen($email) > 255) {
        return [
            'valid' => false,
            'error' => 'Email adresi çok uzun. Maksimum 255 karakter olabilir.'
        ];
    }
    
    // Domain kontrolü - @ işaretinden sonraki kısmı al
    $parts = explode('@', $email);
    if (count($parts) !== 2) {
        return [
            'valid' => false,
            'error' => 'Email formatı hatalı. @ işareti eksik veya fazla.'
        ];
    }
    
    $domain = $parts[1];
    
    // Gecici email servisleri engelle (isteğe bağlı)
    $disposable_domains = [
        'tempmail.com', '10minutemail.com', 'guerrillamail.com',
        'mailinator.com', 'throwaway.email', 'temp-mail.org'
    ];
    
    if (in_array(strtolower($domain), $disposable_domains)) {
        return [
            'valid' => false,
            'error' => 'Geçici email adresleri kullanılamaz. Lütfen kalıcı bir email adrsi girin.'
        ];
    }
    
    return [
        'valid' => true,
        'error' => ''
    ];
}

/**
 * Kullanıcı Adı Validasyonu
 * 
 * Kullanıcı adının güvenlk standartlarına uygunluğunu kontrol eder
 * - Sadece harf, rakam, alt çizgi ve tire içerebilr
 * - 3-30 karakter arası olmalı
 * - Özel karakterler ve boşluk içermemeli
 * 
 * @param string $username Kontrol edileck kullanıcı adı
 * @return array ['valid' => bool, 'error' => string]
 */
function validateUsername($username) {
    // Boşlukları temizle
    $username = trim($username);
    
    // Uzunluk kontrolü - minimum 3, maksimum 30 karakter
    if (strlen($username) < 3) {
        return [
            'valid' => false,
            'error' => 'Kullanıcı adı en az 3 karakter olmalıdır.'
        ];
    }
    
    if (strlen($username) > 30) {
        return [
            'valid' => false,
            'error' => 'Kullanıcı adı maksimum 30 karakter olabilir.'
        ];
    }
    
    // Karakter kontrolü - sadece alfanumerik, alt çizgi ve tire
    if (!preg_match('/^[a-zA-Z0-9_-]+$/', $username)) {
        return [
            'valid' => false,
            'error' => 'Kullanıcı adı sadece harf, rakam, alt çizgi (_) ve tire (-) içerebilir.'
        ];
    }
    
    // Rakamla başlamamalı - guvenlik ve kullanılabilirlik için
    if (preg_match('/^[0-9]/', $username)) {
        return [
            'valid' => false,
            'error' => 'Kullanıcı adı rakamla başlayamaz.'
        ];
    }
    
    // Yasaklı kullanıcı adları - sistem rezerve kelimeler
    $reserved_usernames = [
        'admin', 'administrator', 'root', 'system', 'moderator',
        'webmaster', 'support', 'help', 'api', 'null', 'undefined'
    ];
    
    if (in_array(strtolower($username), $reserved_usernames)) {
        return [
            'valid' => false,
            'error' => 'Bu kullanıcı adı kullanılamaz. Lütfen farklı bir kullanıcı adı seçin.'
        ];
    }
    
    return [
        'valid' => true,
        'error' => ''
    ];
}

/**
 * Tam Ad Validasyonu
 * 
 * Ad soyad alanının doğruluğunu kontol eder
 * 
 * @param string $fullname Kontrol edilecek tam ad
 * @return array ['valid' => bool, 'error' => string]
 */
function validateFullname($fullname) {
    $fullname = trim($fullname);
    
    // Boş kontrolü
    if (empty($fullname)) {
        return [
            'valid' => false,
            'error' => 'Ad soyad boş bırakılamaz.'
        ];
    }
    
    // Uzunluk kontrolü
    if (strlen($fullname) < 2) {
        return [
            'valid' => false,
            'error' => 'Ad soyad en az 2 karakter olmalıdır.'
        ];
    }
    
    if (strlen($fullname) > 100) {
        return [
            'valid' => false,
            'error' => 'Ad soyad maksimum 100 karakter olabilir.'
        ];
    }
    
    // Sadece harf, boşluk ve Türkçe karakterler - sayı içermemeli
    if (!preg_match('/^[a-zA-ZğüşıöçĞÜŞİÖÇ\s]+$/u', $fullname)) {
        return [
            'valid' => false,
            'error' => 'Ad soyad sadece harf ve boşluk içerebilir.'
        ];
    }
    
    return [
        'valid' => true,
        'error' => ''
    ];
}

/**
 * Genel Input Sanitization
 * 
 * Tüm kullanıcı girdilerini temizler
 * XSS ve SQLi saldırılarına karşı ek koruma katmanı
 * 
 * @param mixed $data Temizlenecek veri (string veya array)
 * @return mixed Temizlenmiş veri
 */
function sanitizeData($data) {
    if (is_array($data)) {
        // Array ise her elemanı recursive temizle
        return array_map('sanitizeData', $data);
    }
    
    // String ise temizle
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    
    return $data;
}

/**
 * Form Data Validasyonu - Toplu Kontrol
 * 
 * Birden fazla alanı aynı anda doğrular
 * 
 * @param array $rules Validasyon kuralları
 * @param array $data Doğrulanacak veriler
 * @return array ['valid' => bool, 'errors' => array]
 */
function validateFormData($rules, $data) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? '';
        
        // Required kontrolü
        if (isset($rule['required']) && $rule['required'] && empty($value)) {
            $errors[$field] = ($rule['label'] ?? $field) . ' alanı zorunludur.';
            continue;
        }
        
        // Type kontrolü
        if (isset($rule['type']) && !empty($value)) {
            switch ($rule['type']) {
                case 'email':
                    $result = validateEmail($value);
                    if (!$result['valid']) {
                        $errors[$field] = $result['error'];
                    }
                    break;
                    
                case 'username':
                    $result = validateUsername($value);
                    if (!$result['valid']) {
                        $errors[$field] = $result['error'];
                    }
                    break;
                    
                case 'password':
                    $result = validatePassword($value);
                    if (!$result['valid']) {
                        $errors[$field] = implode(' ', $result['errors']);
                    }
                    break;
                    
                case 'fullname':
                    $result = validateFullname($value);
                    if (!$result['valid']) {
                        $errors[$field] = $result['error'];
                    }
                    break;
            }
        }
    }
    
    return [
        'valid' => empty($errors),
        'errors' => $errors
    ];
}

?>
