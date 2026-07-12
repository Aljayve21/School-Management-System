<?php

class Student extends Model {
    protected string $table = 'students';

    public function findByStudentId(string $studentId): ?array {
        return $this->findWhere('student_id', $studentId);
    }

    public function findByUserId(int $userId): ?array {
        return $this->findWhere('user_id', $userId);
    }

    public function getStudentWithUser(int $id): ?array {
        $sql = "SELECT s.*, u.email, u.status
                FROM students s
                JOIN users u ON s.user_id = u.id
                WHERE s.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function getAllWithUsers(array $conditions = []): array {
        $sql = "SELECT s.*, u.email, u.status
                FROM students s
                JOIN users u ON s.user_id = u.id";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "s.{$col} = ?";
                $params[]  = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $sql .= " ORDER BY s.last_name, s.first_name";
        return $this->rawQuery($sql, $params);
    }

    public function getByGradeLevel(int $gradeLevel): array {
        return $this->getAllWithUsers(['s.grade_level' => $gradeLevel]);
    }

    public function getBySection(int $gradeLevel, string $section): array {
        $sql = "SELECT s.*, u.email, u.status
                FROM students s
                JOIN users u ON s.user_id = u.id
                WHERE s.grade_level = ? AND s.section = ?
                ORDER BY s.last_name, s.first_name";
        return $this->rawQuery($sql, [$gradeLevel, $section]);
    }

    public function getGradeSections(): array {
        return $this->rawQuery(
            "SELECT DISTINCT grade_level, section FROM students ORDER BY grade_level, section"
        );
    }
}