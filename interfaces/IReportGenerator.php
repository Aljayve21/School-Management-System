<?php

interface IReportGenerator {
    public function generateStudentReport(int $studentId, string $academicYear): array;
    public function generateClassReport(int $subjectId, int $semester, string $academicYear): array;
    public function generateTeacherReport(int $teacherId, string $academicYear): array;
    public function getDashboardStats(): array;
}