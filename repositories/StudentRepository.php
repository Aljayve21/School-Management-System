<?php

class StudentRepository extends Model implements IRepository {
    protected string $table = 'students';

    public function findById(int $id): ?array {
        $sql = "SELECT s.*, u.email, u.status
                FROM students s
                JOIN users u ON s.user_id = u.id
                WHERE s.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function findByStudentId(string $studentId): ?array {
        return $this->findWhere('student_id', $studentId);
    }

    public function findByUserId(int $userId): ?array {
        return $this->findWhere('user_id', $userId);
    }

    public function findAll(array $conditions = [], string $orderBy = 'id DESC', int $limit = 0): array {
        $sql    = "SELECT s.*, u.email, u.status FROM students s JOIN users u ON s.user_id = u.id";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "s.{$col} = ?";
                $params[]  = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $sql .= " ORDER BY {$orderBy}";
        if ($limit > 0) { $sql .= " LIMIT ?"; $params[] = $limit; }
        return $this->rawQuery($sql, $params);
    }

    public function getByGradeLevel(int $gradeLevel): array {
        return $this->findAll(['s.grade_level' => $gradeLevel], 's.last_name, s.first_name');
    }

    public function getBySection(int $gradeLevel, string $section): array {
        return $this->findAll(['s.grade_level' => $gradeLevel, 's.section' => $section], 's.last_name, s.first_name');
    }

    public function getGradeSections(): array {
        return $this->rawQuery(
            "SELECT DISTINCT grade_level, section FROM students ORDER BY grade_level, section"
        );
    }

    public function getTotalCount(): int {
        return $this->count();
    }

    public function getCountByGrade(int $gradeLevel): int {
        return $this->count(['grade_level' => $gradeLevel]);
    }
}