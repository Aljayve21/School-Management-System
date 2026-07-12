<?php

class GradeService implements IGradeCalculator {
    private GradeRepository $gradeRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->gradeRepo = new GradeRepository();
        $this->logRepo   = new ActivityLogRepository();
    }

    public function calculateQuarterlyGrade(float $writtenWork, float $performanceTask, float $quarterlyExam): float {
        $writtenWeight  = $writtenWork * 0.30;
        $taskWeight     = $performanceTask * 0.50;
        $examWeight     = $quarterlyExam * 0.20;
        return round($writtenWeight + $taskWeight + $examWeight, 2);
    }

    public function getRemarks(float $grade): string {
        if ($grade >= 90) return 'Outstanding';
        if ($grade >= 85) return 'Very Satisfactory';
        if ($grade >= 80) return 'Satisfactory';
        if ($grade >= 75) return 'Fairly Satisfactory';
        return 'Did Not Meet Expectations';
    }

    public function getGeneralAverage(int $studentId, int $semester, string $academicYear): float {
        return $this->gradeRepo->getGeneralAverage($studentId, $semester, $academicYear);
    }

    public function getStudentGrades(int $studentId, int $semester, string $academicYear): array {
        return $this->gradeRepo->getStudentGrades($studentId, $semester, $academicYear);
    }

    public function getClassGrades(int $subjectId, int $semester, string $academicYear): array {
        return $this->gradeRepo->getClassGrades($subjectId, $semester, $academicYear);
    }

    public function getTeacherGrades(int $teacherId, int $semester, string $academicYear): array {
        return $this->gradeRepo->getTeacherGrades($teacherId, $semester, $academicYear);
    }

    public function create(array $data, int $userId): int {
        $data['quarterly_grade'] = $this->calculateQuarterlyGrade(
            (float) $data['written_work'],
            (float) $data['performance_task'],
            (float) $data['quarterly_exam']
        );
        $data['remarks'] = $this->getRemarks($data['quarterly_grade']);

        $existing = $this->gradeRepo->findByUnique(
            $data['student_id'], $data['subject_id'], $data['semester'], $data['academic_year']
        );
        if ($existing) {
            throw new Exception("Grade already exists for this student, subject, and semester.");
        }

        $id = $this->gradeRepo->create($data);
        $this->logRepo->logActivity($userId, 'Created grade', 'grade', $id, null, $data);
        return $id;
    }

    public function update(int $id, array $data, int $userId): bool {
        $data['quarterly_grade'] = $this->calculateQuarterlyGrade(
            (float) $data['written_work'],
            (float) $data['performance_task'],
            (float) $data['quarterly_exam']
        );
        $data['remarks'] = $this->getRemarks($data['quarterly_grade']);

        $old = $this->gradeRepo->findById($id);
        $result = $this->gradeRepo->update($id, $data);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Updated grade', 'grade', $id, $old, $data);
        }
        return $result;
    }

    public function getSemesters(): array {
        return $this->gradeRepo->getSemesters();
    }

    public function getTotalCount(): int {
        return $this->gradeRepo->getTotalCount();
    }
}