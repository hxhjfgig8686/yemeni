<?php
// endpoints/stats.php

require_once __DIR__ . '/../api/auth.php';
require_once __DIR__ . '/../api/functions.php';

$user = require_auth();

// إحصائيات
$stats = [];

// عدد الأرقام
$stmt = db()->query("SELECT COUNT(*) as total, SUM(is_used) as used FROM numbers WHERE is_deleted = 0");
$stats['numbers'] = $stmt->fetch();

// عدد الرسائل
$stmt = db()->query("SELECT COUNT(*) as total FROM messages");
$stats['total_messages'] = $stmt->fetchColumn();

// آخر 10 رسائل
$stmt = db()->query("SELECT * FROM messages ORDER BY received_at DESC LIMIT 10");
$stats['recent'] = $stmt->fetchAll();

sendSuccess($stats);
?>
