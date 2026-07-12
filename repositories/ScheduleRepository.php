<?php

class ScheduleRepository extends Model implements IRepository {
    protected string $table = 'schedules';

    public function findById(int $id): ?array {
        $sql = "SELECT sc.*, s.subject_name, s.subject_code,
                       t.first_name AS teacher_first, t.last_name AS teacher_last
                FROM schedules sc
                JOIN subjects s ON sc.subject_id = s.id
                JOIN teachers t ON sc.teacher_id = t.id
                WHERE sc.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function findAll(array $conditions = [], string $orderBy = 'id DESC', int $limit = 0): array {
        $sql = "SELECT sc.*, s.subject_name, s.subject_code,
                       t.first_name AS teacher_first, t.last_name AS teacher_last
                FROM schedules sc
                JOIN subjects s ON sc.subject_id = s.id
                JOIN teachers t ON sc.teacher_id = t.id";
        $params = [];
        if (!empty($conditions)) {
            $clauses = [];
            foreach ($conditions as $col => $val) {
                $clauses[] = "sc.{$col} = ?";
                $params[]  = $val;
            }
            $sql .= " WHERE " . implode(' AND ', $clauses);
        }
        $sql .= " ORDER BY {$orderBy}";
        if ($limit > 0) { $sql .= " LIMIT ?"; $params[] = $limit; }
        return $this->rawQuery($sql, $params);
    }

    public function getByTeacher(int $teacherId): array {
        return $this->findAll(
            ['teacher_id' => $teacherId],
            "FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), sc.time_start"
        );
    }

    public function getByStudent(int $gradeLevel, string $section): array {
        return $this->findAll(
            ['grade_level' => $gradeLevel, 'section' => $section],
            "FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), sc.time_start"
        );
    }

    public function checkConflict(int $teacherId, string $day, string $timeStart, string $timeEnd, int $exceptId = 0): bool {
        $sql = "SELECT COUNT(*) as c FROM schedules
                WHERE teacher_id = ? AND day_of_week = ? AND time_start < ? AND time_end > ?";
        $params = [$teacherId, $day, $timeEnd, $timeStart];
        if ($exceptId > 0) { $sql .= " AND id != ?"; $params[] = $exceptId; }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch()['c'] > 0;
    }

    public function getSections(): array {
        return $this->rawQuery(
            "SELECT DISTINCT grade_level, section FROM schedules ORDER BY grade_level, section"
        );
    }

    public function getTotalCount(): int {
        return $this->count();
    }
}