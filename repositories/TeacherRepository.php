<?php

class TeacherRepository extends Model implements IRepository {
    protected string $table = 'teachers';

    public function findById(int $id): ?array {
        $sql = "SELECT t.*, u.email, u.status
                FROM teachers t
                JOIN users u ON t.user_id = u.id
                WHERE t.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function findByTeacherId(string $teacherId): ?array {
        return $this->findWhere('teacher_id', $teacherId);
    }

    public function findByUserId(int $userId): ?array {
        return $this->findWhere('user_id', $userId);
    }

    public function findAll(array $conditions = [], string $orderBy = 'id DESC', int $limit = 0): array {
        $sql    = "SELECT t.*, u.email, u.status FROM teachers t JOIN users u ON t.user_id = u.id";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "t.{$col} = ?";
                $params[]  = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $sql .= " ORDER BY {$orderBy}";
        if ($limit > 0) { $sql .= " LIMIT ?"; $params[] = $limit; }
        return $this->rawQuery($sql, $params);
    }

    public function getByDepartment(string $department): array {
        return $this->findAll(['t.department' => $department], 't.last_name, t.first_name');
    }

    public function getTotalCount(): int {
        return $this->count();
    }
}