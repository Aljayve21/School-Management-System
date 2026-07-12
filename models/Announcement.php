<?php

class Announcement extends Model {
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

    public function getAll(): array {
        $sql = "SELECT a.*, u.email AS poster_email
                FROM announcements a
                JOIN users u ON a.posted_by = u.id
                ORDER BY a.created_at DESC";
        return $this->rawQuery($sql);
    }

    public function getActiveAnnouncements(): array {
        return $this->rawQuery(
            "SELECT * FROM announcements WHERE is_active = 1 ORDER BY priority DESC, created_at DESC"
        );
    }

    public function toggleActive(int $id): bool {
        $current = $this->findById($id);
        if (!$current) return false;
        $newStatus = $current['is_active'] ? 0 : 1;
        return $this->update($id, ['is_active' => $newStatus]);
    }
}