<?php
/**
 * Çıkış Sayfası
 * Session'u güvenli şekilde sonlandırır
 */

// Session başlatma - logout için gerekli
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();
header("Location: index.php");
exit;
