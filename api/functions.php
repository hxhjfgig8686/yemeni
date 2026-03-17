<?php
// api/functions.php

// ==========================
// Database Connection
// ==========================
function db() {
    static $pdo;

    if ($pdo === null) {
        $pdo = new PDO(
            "pgsql:host=dpg-d6s9qvjuibrs73e7oklg-a.oregon-postgres.render.com;dbname=sms_api",
            "sms_api_user",
            "U30dJ4ty5HXDeAJfMfmGbxInOxpiQZDM"
        );

        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $pdo;
}

// ==========================
// Response Helpers
// ==========================
function sendResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

function sendError($message, $status = 400) {
    sendResponse([
        'success' => false,
        'error' => $message
    ], $status);
}

function sendSuccess($data = [], $message = '') {
    $response = ['success' => true];
    if ($message) $response['message'] = $message;
    if (!empty($data)) $response['data'] = $data;
    sendResponse($response);
}

// ==========================
// Number Helpers
// ==========================
function cleanNumber($number) {
    return preg_replace('/[^0-9]/', '', $number);
}

function extractCountryCode($number) {
    $number = cleanNumber($number);

    // تعريف الدول (تقدر تعدلها)
    $codes = [
        '966' => 'Saudi Arabia',
        '20' => 'Egypt',
        '971' => 'UAE',
        '1' => 'USA'
    ];

    uksort($codes, function($a, $b) {
        return strlen($b) - strlen($a);
    });

    foreach (array_keys($codes) as $code) {
        if (strpos($number, $code) === 0) {
            return $code;
        }
    }

    return 'unknown';
}

// ==========================
// OTP Extraction
// ==========================
function extractOTP($message) {
    $patterns = [
        '/\b(\d{4,8})\b/',
        '/code[:\s]*(\d{4,8})/i',
        '/كود[:\s]*(\d{4,8})/u',
        '/otp[:\s]*(\d{4,8})/i',
        '/verification[:\s]*(\d{4,8})/i',
        '/(\d{3})[- ]?(\d{3,4})/',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $message, $matches)) {
            if (count($matches) > 2) {
                return $matches[1] . $matches[2];
            }
            return $matches[1];
        }
    }

    return null;
}

// ==========================
// Service Detection
// ==========================
function detectService($message) {
    $message_lower = strtolower($message);

    $services = [
        'WHATSAPP' => ['whatsapp', 'واتساب', 'واتس'],
        'FACEBOOK' => ['facebook', 'فيسبوك', 'fb'],
        'INSTAGRAM' => ['instagram', 'انستقرام'],
        'TELEGRAM' => ['telegram', 'تيليجرام'],
        'TWITTER' => ['twitter', 'تويتر', 'x.com'],
        'GOOGLE' => ['google', 'gmail'],
        'TIKTOK' => ['tiktok'],
        'SNAPCHAT' => ['snapchat'],
        'UBER' => ['uber'],
        'AMAZON' => ['amazon'],
        'NETFLIX' => ['netflix'],
        'MICROSOFT' => ['microsoft'],
        'APPLE' => ['apple'],
    ];

    foreach ($services as $service => $keywords) {
        foreach ($keywords as $keyword) {
            if (strpos($message_lower, $keyword) !== false) {
                return $service;
            }
        }
    }

    return 'GENERAL';
}

// ==========================
// Mask Number
// ==========================
function maskNumber($number) {
    if (strlen($number) > 8) {
        return substr($number, 0, 3) . '••••' . substr($number, -4);
    }
    return $number;
}

// ==========================
// Validation
// ==========================
function validateInput($data, $required) {
    foreach ($required as $field) {
        if (!isset($data[$field]) || empty($data[$field])) {
            return false;
        }
    }
    return true;
}
?>