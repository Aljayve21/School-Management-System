<?php

class Grade extends Model {
    protected string $table = 'grades';

    public function getFullGrade(int $id): ?array {
        $sql = "SELECT g.*, st.first_name AS student_first, st.last_name AS student_last, st.student_id AS student_number,
                       s.subject_name, s.subject_code
                FROM grades g
                JOIN students st ON g.student_id = st.id
                JOIN subjects s ON g.subject_id = s.id
                WHERE g.id = ?";
        return $this->rawQuerySingle($sql, [$id]);
    }

    public function getStudentGrades(int $studentId, int $semester, string $academicYear): array {
        $sql = "SELECT g.*, s.subject_name, s.subject_code
                FROM grades g
                JOIN subjects s ON g.subject_id = s.id
                WHERE g.student_id = ? AND g.semester = ? AND g.academic_year = ?
                ORDER BY s.subject_name";
        return $this->rawQuery($sql, [$studentId, $semester, $academicYear]);
    }

    public function getClassGrades(int $subjectId, int $semester, string $academicYear): array {
        $sql = "SELECT g.*, st.first_name AS student_first, st.last_name AS student_last, st.student_id AS student_number
                FROM grades g
                JOIN students st ON g.student_id = st.id
                WHERE g.subject_id = ? AND g.semester = ? AND g.academic_year = ?
                ORDER BY st.last_name, st.first_name";
        return $this->rawQuery($sql, [$subjectId, $semester, $academicYear]);
    }

    public function getTeacherGrades(int $teacherId, int $semester, string $academicYear): array {
        $sql = "SELECT g.*, st.first_name AS student_first, st.last_name AS student_last,
                       s.subject_name, s.subject_code
                FROM grades g
                JOIN students st ON g.student_id = st.id
                JOIN subjects s ON g.subject_id = s.id
                WHERE g.teacher_id = ? AND g.semester = ? AND g.academic_year = ?
                ORDER BY s.subject_name, st.last_name, st.first_name";
        return $this->rawQuery($sql, [$teacherId, $semester, $academicYear]);
    }

    public function findByUnique(int $studentId, int $subjectId, int $semester, string $academicYear): ?array {
        $sql = "SELECT * FROM grades
                WHERE student_id = ? AND subject_id = ? AND semester = ? AND academic_year = ?";
        return $this->rawQuerySingle($sql, [$studentId, $subjectId, $semester, $academicYear]);
    }

    public function getSemesters(): array {
        return $this->rawQuery("SELECT DISTINCT semester, academic_year FROM grades ORDER BY academic_year DESC, semester DESC");
    }
}