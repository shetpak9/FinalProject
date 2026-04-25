<?php

class LogGateway {
    private $pdo;

    public function __construct(Database $database) {
        $this->pdo = $database->getconnection();
        $this->ensureTableExists();
    }

    private function ensureTableExists(): void {
        $sql = "CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            action VARCHAR(255) NOT NULL,
            entity_type VARCHAR(100) NOT NULL,
            entity_id INT,
            details JSON,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            ip_address VARCHAR(45),
            user_agent TEXT,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
            INDEX (user_id, created_at),
            INDEX (action),
            INDEX (entity_type)
        )";
        
        try {
            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            // Table might already exist
        }
    }

    public function log(
        int $userId,
        string $action,
        string $entityType,
        ?int $entityId = null,
        ?array $details = null
    ): void {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $sql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, details, ip_address, user_agent)
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            $userId,
            $action,
            $entityType,
            $entityId,
            $details ? json_encode($details) : null,
            $ipAddress,
            $userAgent
        ]);
    }

    public function getAllLogs(
        int $limit = 100,
        int $offset = 0,
        ?string $action = null,
        ?string $entityType = null
    ): array {
        $sql = "SELECT al.*, u.name, u.role FROM audit_logs al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE 1=1";

        if ($action) {
            $sql .= " AND al.action = ?";
        }
        if ($entityType) {
            $sql .= " AND al.entity_type = ?";
        }

        $sql .= " ORDER BY al.created_at DESC LIMIT ? OFFSET ?";

        $stmt = $this->pdo->prepare($sql);
        $params = [];

        if ($action) $params[] = $action;
        if ($entityType) $params[] = $entityType;
        $params[] = $limit;
        $params[] = $offset;

        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function getUserLogs(
        int $userId,
        int $limit = 50,
        int $offset = 0
    ): array {
        $sql = "SELECT * FROM audit_logs
                WHERE user_id = ?
                ORDER BY created_at DESC
                LIMIT ? OFFSET ?";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $limit, $offset]);
        return $stmt->fetchAll();
    }

    public function getLogCount(?string $action = null, ?string $entityType = null): int {
        $sql = "SELECT COUNT(*) as count FROM audit_logs WHERE 1=1";

        if ($action) {
            $sql .= " AND action = ?";
        }
        if ($entityType) {
            $sql .= " AND entity_type = ?";
        }

        $stmt = $this->pdo->prepare($sql);
        $params = [];

        if ($action) $params[] = $action;
        if ($entityType) $params[] = $entityType;

        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    }
}
?>
