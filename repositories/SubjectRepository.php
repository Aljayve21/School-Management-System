<?php

class SubjectRepository extends Model implements IRepository {
    protected string $table = 'subjects';

    public function findByCode(string $code): ?array {
        return $this->findWhere('subject_code', $code);
    }

    public function getActive(): array {
        return $this->findAll(['status' => 'active'], 'subject_name');
    }

    public function getByGradeLevel(int $gradeLevel): array {
        return $this->findAll(['grade_level' => $gradeLevel], 'subject_name');
    }

    public function getTeacherSubjects(int $teacherId): array {
        $sql = "SELECT s.*
                FROM subjects s
                JOIN teacher_subjects ts ON s.id = ts.subject_id
                WHERE ts.teacher_id = ?
                ORDER BY s.subject_name";
        return $this->rawQuery($sql, [$teacherId]);
    }

    public function assignTeacher(int $subjectId, int $teacherId): bool {
        $stmt = $this->db->prepare("INSERT IGNORE INTO teacher_subjects (subject_id, teacher_id) VALUES (?, ?)");
        return $stmt->execute([$subjectId, $teacherId]);
    }

    public function removeTeacher(int $subjectId, int $teacherId): bool {
        $stmt = $this->db->prepare("DELETE FROM teacher_subjects WHERE subject_id = ? AND teacher_id = ?");
        return $stmt->execute([$subjectId, $teacherId]);
    }

    public function getAssignedTeachers(int $subjectId): array {
        $sql = "SELECT t.*
                FROM teachers t
                JOIN teacher_subjects ts ON t.id = ts.teacher_id
                WHERE ts.subject_id = ?";
        return $this->rawQuery($sql, [$subjectId]);
    }

    public function getTotalCount(): int {
        return $this->count();
    }
}