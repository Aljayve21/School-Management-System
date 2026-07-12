<?php

class Teacher extends Model {
    protected string $table = 'teachers';

    public function findByTeacherId(string $teacherId): ?array {
        return $this->findWhere('teacher_id', $teacherId);
    }

    public function findByUserId(int $userId): ?array {
        return $this->findWhere('user_id', $userId);
    }

    public function getTeacherWithUser(int $id): ?array {
        $sql = "SELECT t.*, u.email, u.status
                FROM teachers t
                JOIN users u ON t.user_id = u.id
                WHERE t.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function getAllWithUsers(): array {
        $sql = "SELECT t.*, u.email, u.status
                FROM teachers t
                JOIN users u ON t.user_id = u.id
                ORDER BY t.last_name, t.first_name";
        return $this->rawQuery($sql);
    }

    public function getByDepartment(string $department): array {
        $sql = "SELECT t.*, u.email, u.status
                FROM teachers t
                JOIN users u ON t.user_id = u.id
                WHERE t.department = ?
                ORDER BY t.last_name, t.first_name";
        return $this->rawQuery($sql, [$department]);
    }

    public function getSubjects(int $teacherId): array {
        $sql = "SELECT sub.*
                FROM subjects sub
                JOIN teacher_subjects ts ON sub.id = ts.subject_id
                WHERE ts.teacher_id = ?";
        return $this->rawQuery($sql, [$teacherId]);
    }
}