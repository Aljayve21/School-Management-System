<?php

interface IGradeCalculator {
    public function calculateQuarterlyGrade(float $writtenWork, float $performanceTask, float $quarterlyExam): float;
    public function getRemarks(float $grade): string;
    public function getGeneralAverage(int $studentId, int $semester, string $academicYear): float;
}