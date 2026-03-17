<?php
// api/config.php

// PostgreSQL on Render
define('DATABASE_URL', getenv('DATABASE_URL') ?: 'dpg-d6s9qvjuibrs73e7oklg-a/sms_api');

if (!DATABASE_URL) {
 die('DATABASE_URL is missing in Render Environment Variables.');
}

$parts = parse_url(DATABASE_URL);
define('DB_HOST', $parts['host']);
define('DB_PORT', $parts['port'] ?? 5432);
define('DB_NAME', ltrim($parts['path'] ?? '', '/'));
define('DB_USER', $parts['user'] ?? 'sms_api_user');
define('DB_PASS', $parts['pass'] ?? 'U30dJ4ty5HXDeAJfMfmGbxInOxpiQZDM');

// إعدادات عامة
define('API_DEBUG', true);
define('API_TIMEZONE', 'Africa/Cairo');
define('API_VERSION', '1.0.0');

// مفاتيح API المسموح بها
define('API_ALLOWED_KEYS', json_encode([
    'sk_cc1480ac5e3a4818e07fb4b0674bc2a72228372220dba26ac4579cfd4eda903b'
]));

// أكواد الدول
define('COUNTRY_CODES', json_encode([
    '1' => 'USA',
    '20' => 'EGYPT',
    '966' => 'SAUDI',
    '971' => 'UAE',
    '44' => 'UK',
    '33' => 'FRANCE'
]));

// رسائل الخطأ
define('ERROR_INVALID_KEY', 'مفتاح API غير صالح');
define('ERROR_INVALID_AUTH', 'بيانات المصادقة غير صحيحة');
define('ERROR_MISSING_PARAMS', 'بيانات ناقصة');
define('ERROR_NUMBER_EXISTS', 'الرقم موجود بالفعل');
define('ERROR_NUMBER_NOT_FOUND', 'الرقم غير موجود');
define('SUCCESS_OK', 'تم بنجاح');

date_default_timezone_set(API_TIMEZONE);
?>