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
    private string $port;
    private string $sslMode;
    private ?PDO $conn = null;

    public function __construct()
    {
        $databaseUrl = AuthConfig::get('DATABASE_URL', AuthConfig::get('POSTGRES_URL'));
        if ($databaseUrl !== '') {
            $this->configureFromUrl($databaseUrl);
            return;
        }

        $this->host = AuthConfig::get('DB_HOST', 'localhost');
        $this->database = AuthConfig::get('DB_NAME', 'unityfund_db');
        $this->username = AuthConfig::get('DB_USER', 'postgres');
        $this->password = AuthConfig::get('DB_PASS', '');
        $this->port = AuthConfig::get('DB_PORT', '5432');
        $this->sslMode = AuthConfig::get('DB_SSLMODE', 'prefer');
    }

    public function connect(): PDO
    {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    $this->dsn(),
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

    private function configureFromUrl(string $databaseUrl): void
    {
        $parts = parse_url($databaseUrl);
        if ($parts === false || empty($parts['host']) || empty($parts['path'])) {
            throw new RuntimeException('DATABASE_URL is not a valid PostgreSQL connection string.');
        }

        $this->host = $parts['host'];
        $this->database = ltrim($parts['path'], '/');
        $this->username = isset($parts['user']) ? urldecode($parts['user']) : '';
        $this->password = isset($parts['pass']) ? urldecode($parts['pass']) : '';
        $this->port = isset($parts['port']) ? (string) $parts['port'] : '5432';

        $query = [];
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $query);
        }

        $this->sslMode = $query['sslmode'] ?? AuthConfig::get('DB_SSLMODE', 'require');
    }

    private function dsn(): string
    {
        return sprintf(
            'pgsql:host=%s;port=%s;dbname=%s;sslmode=%s',
            $this->host,
            $this->port,
            $this->database,
            $this->sslMode
        );
    }
}
