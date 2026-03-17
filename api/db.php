<?php
// api/db.php

require_once __DIR__ . '/config.php';

class Database {
 private static ?Database $instance = null;
 private PDO $connection;

 private function __construct() {
 try {
 $dsn = "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME;

 $this->connection = new PDO(
 $dsn,
 DB_USER,
 DB_PASS,
 [
 PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
 PDO::ATTR_EMULATE_PREPARES => false,
 ]
 );

 $this->connection->exec("SET client_encoding TO 'UTF8'");
 } catch (PDOException $e) {
 die(json_encode([
 'success' => false,
 'error' => 'Database connection failed: ' . $e->getMessage()
 ]));
 }
 }

 public static function getInstance(): Database {
 if (self::$instance === null) {
 self::$instance = new self();
 }
 return self::$instance;
 }

 public function getConnection(): PDO {
 return $this->connection;
 }
}

// Helper function (اختياري)
function db(): PDO {
 return Database::getInstance()->getConnection();
}
