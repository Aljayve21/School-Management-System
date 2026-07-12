<?php

class AnnouncementRepository extends Model implements IRepository {
    protected string $table = 'announcements';

    public function getActive(): array {
        $sql = "SELECT a.*, u.email AS poster_email
                FROM announcements a
                JOIN users u ON a.posted_by = u.id
                WHERE a.is_active = 1
                ORDER BY a.priority DESC, a.created_at DESC";
        return $this->rawQuery($sql);
    }

    public function getForRole(string $role): array {
        $sql = "SELECT a.*, u.email AS poster_email
                FROM announcements a
                JOIN users u ON a.posted_by = u.id
                WHERE a.is_active = 1 AND (a.target_role = 'all' OR a.target_role = ?)
                ORDER BY a.priority DESC, a.created_at DESC";
        return $this->rawQuery($sql, [$role]);
    }

    public function findAll(array $conditions = [], string $orderBy = 'id DESC', int $limit = 0): array {
        $sql = "SELECT a.*, u.email AS poster_email
                FROM announcements a
                JOIN users u ON a.posted_by = u.id";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "a.{$col} = ?";
                $params[]  = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $sql .= " ORDER BY {$orderBy}";
        if ($limit > 0) { $sql .= " LIMIT ?"; $params[] = $limit; }
        return $this->rawQuery($sql, $params);
    }

    public function toggleActive(int $id): bool {
        $current = (new Announcement())->findById($id);
        if (!$current) return false;
        return $this->update($id, ['is_active' => $current['is_active'] ? 0 : 1]);
    }

    public function getTotalCount(): int {
        return $this->count();
    }

    public function getActiveCount(): int {
        return $this->count(['is_active' => 1]);
    }
}