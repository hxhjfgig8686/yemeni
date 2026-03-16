<?php
// install.php

require_once __DIR__ . '/api/config.php';
require_once __DIR__ . '/api/db.php';

header('Content-Type: text/html; charset=utf-8');

echo "<pre style='direction:ltr;background:#f4f4f4;padding:20px;font-family:monospace;white-space:pre-wrap'>";

$lockFile = __DIR__ . '/.installed.lock';
$sqlFile = __DIR__ . '/install.sql';

try {
 if (file_exists($lockFile)) {
 http_response_code(403);
 echo "❌ Installation is locked. Remove .installed.lock only if you really need to reinstall.\n";
 exit;
 }

 echo "🚀 Starting SMS API installation...\n";

 $conn = db();
 // اجعل PDO يرمي أخطاء واضحة
 if ($conn instanceof PDO) {
 $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 }

 if (!file_exists($sqlFile)) {
 throw new Exception("install.sql not found: $sqlFile");
 }

 $sql = file_get_contents($sqlFile);
 if ($sql === false || trim($sql) === '') {
 throw new Exception("install.sql is empty or unreadable.");
 }

 // تقسيم بسيط للأوامر (مناسب لمعظم ملفات SQL البسيطة)
 $queries = array_filter(array_map('trim', preg_split("/;\\s*\\n/", $sql)));
 $count = 0;

 $conn->beginTransaction();
 foreach ($queries as $query) {
 if ($query === '') continue;
 if (preg_match('/^(--|#|\\/\\*)/u', $query)) continue; // تخطّي التعليقات
 $conn->exec($query);
 $count++;
 }
 $conn->commit();

 // قفل التثبيت
 file_put_contents($lockFile, "installed_at=" . date('c') . "\n");

 echo "✅ Done. Executed $count SQL statements successfully.\n";
 echo "🔒 Installation locked: .installed.lock created.\n";
 echo "🧹 IMPORTANT: delete/rename install.php after confirming everything works.\n";

} catch (Throwable $e) {
 if (isset($conn) && $conn instanceof PDO && $conn->inTransaction()) {
 $conn->rollBack();
 }
 http_response_code(500);
 echo "❌ Install failed:\n";
 echo $e->getMessage() . "\n";
}

echo "</pre>";
