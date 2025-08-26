<?php

namespace StepTracker\Pdo;

class DatabasePdo
{
    private static ?\mysqli $connection = null;

    public function __construct()
    {
        if (self::$connection === null) {
            include __DIR__ . '/../../../../../config/config.php';

            self::$connection = new \mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);

            if (self::$connection->connect_error) {
                throw new \RuntimeException('MySQL connection failed: ' . self::$connection->connect_error);
            }

        // Set charset (recommended)
            self::$connection->set_charset('utf8mb4');
        }
    }

    // Helper: prepare, bind params, execute statement
    private function executeStatement(string $sql, array $params = []): \mysqli_stmt
    {
        $stmt = self::$connection->prepare($sql);
        if ($stmt === false) {
            throw new \RuntimeException('Statement prepare failed: ' . self::$connection->error);
        }

        if (!empty($params)) {
            // Determine types string for bind_param
            $types = '';
            $values = [];
            foreach ($params as $param) {
                if (is_int($param)) {
                    $types .= 'i';
                } elseif (is_float($param)) {
                    $types .= 'd';
                } elseif (is_string($param)) {
                    $types .= 's';
                } elseif (is_null($param)) {
                    $types .= 's'; // still bind as string
                } else {
                    $types .= 'b'; // blob or unknown
                }
                $values[] = $param;
            }

            // bind_param requires references
            $bindNames = [];
            $bindNames[] = & $types;
            for ($i = 0; $i < count($values); $i++) {
                $bindNames[] = & $values[$i];
            }

            if (!call_user_func_array([$stmt, 'bind_param'], $bindNames)) {
                throw new \RuntimeException('Parameter binding failed: ' . $stmt->error);
            }
        }

        if (!$stmt->execute()) {
            throw new \RuntimeException('Statement execution failed: ' . $stmt->error);
        }

        return $stmt;
    }

    // SELECT multiple rows
    public function select(string $sql, array $params = []): array
    {
        $stmt = $this->executeStatement($sql, $params);
        $result = $stmt->get_result();
        $data = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
        return $data;
    }

    // SELECT single row
    public function selectOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->executeStatement($sql, $params);
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        $stmt->close();
        return $data === null ? null : $data;
    }

    // INSERT, UPDATE, DELETE — returns affected rows
    public function execute(string $sql, array $params = []): int
    {
        $stmt = $this->executeStatement($sql, $params);
        $affectedRows = $stmt->affected_rows;
        $stmt->close();
        return $affectedRows;
    }

    // INSERT with last inserted ID return
    public function insert(string $sql, array $params = []): int
    {
        $this->executeStatement($sql, $params)->close();
        return self::$connection->insert_id;
    }

    // Raw query without params (be careful!)
    public function query(string $sql)
    {
        return self::$connection->query($sql);
    }

    // Close connection explicitly (optional)
    public function close(): void
    {
        self::$connection->close();
    }
}
