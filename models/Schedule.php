<?php

class Schedule extends Model {
    protected string $table = 'schedules';

    public function getFullSchedule(int $id): ?array {
        $sql = "SELECT sc.*, s.subject_name, s.subject_code,
                       t.first_name AS teacher_first, t.last_name AS teacher_last
                FROM schedules sc
                JOIN subjects s ON sc.subject_id = s.id
                JOIN teachers t ON sc.teacher_id = t.id
                WHERE sc.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function getAllFull(): array {
        $sql = "SELECT sc.*, s.subject_name, s.subject_code,
                       t.first_name AS teacher_first, t.last_name AS teacher_last
                FROM schedules sc
                JOIN subjects s ON sc.subject_id = s.id
                JOIN teachers t ON sc.teacher_id = t.id
                ORDER BY sc.grade_level, sc.section, FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), sc.time_start";
        return $this->rawQuery($sql);
    }

    public function getByTeacher(int $teacherId): array {
        $sql = "SELECT sc.*, s.subject_name, s.subject_code
                FROM schedules sc
                JOIN subjects s ON sc.subject_id = s.id
                WHERE sc.teacher_id = ?
                ORDER BY FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), sc.time_start";
        return $this->rawQuery($sql, [$teacherId]);
    }

    public function getByStudent(int $gradeLevel, string $section): array {
        $sql = "SELECT sc.*, s.subject_name, s.subject_code,
                       t.first_name AS teacher_first, t.last_name AS teacher_last
                FROM schedules sc
                JOIN subjects s ON sc.subject_id = s.id
                JOIN teachers t ON sc.teacher_id = t.id
                WHERE sc.grade_level = ? AND sc.section = ?
                ORDER BY FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), sc.time_start";
        return $this->rawQuery($sql, [$gradeLevel, $section]);
    }

    public function checkConflict(int $teacherId, string $day, string $timeStart, string $timeEnd, int $exceptId = 0): bool {
        $sql = "SELECT COUNT(*) as c FROM schedules
                WHERE teacher_id = ? AND day_of_week = ?
                AND time_start < ? AND time_end > ?";
        $params = [$teacherId, $day, $timeEnd, $timeStart];
        if ($exceptId > 0) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetch()['c'] > 0;
    }
}