<?php

class ActivityLogService implements IActivityLogger {
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->logRepo = new ActivityLogRepository();
    }

    public function log(int $userId, string $action, ?string $entityType = null, ?int $entityId = null, $oldValues = null, $newValues = null): void {
        $this->logRepo->logActivity($userId, $action, $entityType, $entityId, $oldValues, $newValues);
    }

    public function getLogs(array $conditions = [], int $limit = 50): array {
        if (!empty($conditions)) {
            $sql = "SELECT al.*, u.email AS user_email
                    FROM activity_logs al
                    LEFT JOIN users u ON al.user_id = u.id";
            $clauses = [];
            $params  = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "al.{$col} = ?";
                $params[]  = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
            $sql .= " ORDER BY al.created_at DESC LIMIT ?";
            $params[] = $limit;
            return $this->logRepo->rawQuery($sql, $params);
        }
        return $this->logRepo->getRecent($limit);
    }

    public function getLogsByUser(int $userId, int $limit = 50): array {
        return $this->logRepo->getByUser($userId, $limit);
    }

    public function getLogsByEntity(string $entityType, int $entityId): array {
        return $this->logRepo->getByEntity($entityType, $entityId);
    }

    public function clearOld(int $days = 90): bool {
        return $this->logRepo->clearOld($days);
    }

    public function getTotalCount(): int {
        return $this->logRepo->getTotalCount();
    }
}