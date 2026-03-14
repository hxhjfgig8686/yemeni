<?php
// api/config.php

// إعدادات قاعدة البيانات
define('DB_HOST', getenv('MYSQLHOST') ?: 'localhost');
define('DB_NAME', getenv('MYSQLDATABASE') ?: 'sms_api');
define('DB_USER', getenv('MYSQLUSER') ?: 'root');
define('DB_PASS', getenv('MYSQLPASSWORD') ?: '');

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