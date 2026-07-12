<?php

class ActivityLog extends Model {
    protected string $table = 'activity_logs';

    public function getRecent(int $limit = 50): array {
        $sql = "SELECT al.*, u.email AS user_email
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT ?";
        return $this->rawQuery($sql, [$limit]);
    }

    public function getByUser(int $userId, int $limit = 50): array {
        $sql = "SELECT al.*, u.email AS user_email
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.user_id = ?
                ORDER BY al.created_at DESC
                LIMIT ?";
        return $this->rawQuery($sql, [$userId, $limit]);
    }

    public function getByEntity(string $entityType, int $entityId): array {
        $sql = "SELECT al.*, u.email AS user_email
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                WHERE al.entity_type = ? AND al.entity_id = ?
                ORDER BY al.created_at DESC";
        return $this->rawQuery($sql, [$entityType, $entityId]);
    }

    public function logActivity(int $userId, string $action, ?string $entityType = null, ?int $entityId = null, $oldValues = null, $newValues = null): void {
        $data = [
            'user_id'     => $userId,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'old_values'  => $oldValues ? json_encode($oldValues) : null,
            'new_values'  => $newValues ? json_encode($newValues) : null,
            'ip_address'  => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            'user_agent'  => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];
        $this->create($data);
    }

    public function clearOld(int $days = 90): bool {
        $stmt = $this->db->prepare(
            "DELETE FROM {$this->table} WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)"
        );
        return $stmt->execute([$days]);
    }
}