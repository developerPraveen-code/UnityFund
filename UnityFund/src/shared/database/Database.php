<?php

// SHARED DATABASE CONNECTION
// BCE Support Role: Database helper
// Purpose: Provides PostgreSQL connection for persistent storage.
require_once __DIR__ . '/../../auth/AuthConfig.php';

class Database
{
    private string $host;
    private string $database;
    private string $username;
    private string $password;
    private ?PDO $conn = null;

    public function __construct()
    {
        $this->host = AuthConfig::get('DB_HOST', 'localhost');
        $this->database = AuthConfig::get('DB_NAME', 'unityfund_db');
        $this->username = AuthConfig::get('DB_USER', 'postgres');
        $this->password = AuthConfig::get('DB_PASS', '');
    }

    public function connect(): PDO
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "pgsql:host={$this->host};dbname={$this->database}",
                    $this->username,
                    $this->password
                );
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                throw new RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
            }
        }
        return $this->conn;
    }
}
