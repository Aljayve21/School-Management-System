<?php

class ReportService implements IReportGenerator {
    private StudentRepository $studentRepo;
    private TeacherRepository $teacherRepo;
    private GradeRepository $gradeRepo;
    private SubjectRepository $subjectRepo;
    private ScheduleRepository $scheduleRepo;
    private UserRepository $userRepo;
    private AnnouncementRepository $announcementRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->studentRepo     = new StudentRepository();
        $this->teacherRepo     = new TeacherRepository();
        $this->gradeRepo       = new GradeRepository();
        $this->subjectRepo     = new SubjectRepository();
        $this->scheduleRepo    = new ScheduleRepository();
        $this->userRepo        = new UserRepository();
        $this->announcementRepo = new AnnouncementRepository();
        $this->logRepo         = new ActivityLogRepository();
    }

    public function getDashboardStats(): array {
        return [
            'total_students'    => $this->studentRepo->getTotalCount(),
            'total_teachers'    => $this->teacherRepo->getTotalCount(),
            'total_subjects'    => $this->subjectRepo->getTotalCount(),
            'total_schedules'   => $this->scheduleRepo->getTotalCount(),
            'total_users'       => $this->userRepo->getTotalCount(),
            'total_announcements' => $this->announcementRepo->getTotalCount(),
        ];
    }

    public function generateStudentReport(int $studentId, string $academicYear): array {
        $student = $this->studentRepo->findById($studentId);
        if (!$student) return [];

        $semesters = [];
        for ($sem = 1; $sem <= 2; $sem++) {
            $grades = $this->gradeRepo->getStudentGrades($studentId, $sem, $academicYear);
            $average = !empty($grades)
                ? round(array_sum(array_column($grades, 'quarterly_grade')) / count($grades), 2)
                : 0;
            $semesters[$sem] = [
                'grades'  => $grades,
                'average' => $average,
                'remarks' => $this->getRemarksFromAverage($average),
            ];
        }

        return [
            'student'   => $student,
            'year'      => $academicYear,
            'semesters' => $semesters,
        ];
    }

    public function generateClassReport(int $subjectId, int $semester, string $academicYear): array {
        $subject = $this->subjectRepo->findById($subjectId);
        $grades  = $this->gradeRepo->getClassGrades($subjectId, $semester, $academicYear);

        $classAverage = !empty($grades)
            ? round(array_sum(array_column($grades, 'quarterly_grade')) / count($grades), 2)
            : 0;

        $highest = !empty($grades) ? max(array_column($grades, 'quarterly_grade')) : 0;
        $lowest  = !empty($grades) ? min(array_column($grades, 'quarterly_grade')) : 0;

        return [
            'subject'        => $subject,
            'semester'       => $semester,
            'academic_year'  => $academicYear,
            'grades'         => $grades,
            'class_average'  => $classAverage,
            'highest_grade'  => $highest,
            'lowest_grade'   => $lowest,
            'total_students' => count($grades),
        ];
    }

    public function generateTeacherReport(int $teacherId, string $academicYear): array {
        $teacher   = $this->teacherRepo->findById($teacherId);
        $subjects  = $this->subjectRepo->getTeacherSubjects($teacherId);
        $schedules = $this->scheduleRepo->getByTeacher($teacherId);

        $subjectStats = [];
        foreach ($subjects as $subject) {
            for ($sem = 1; $sem <= 2; $sem++) {
                $grades = $this->gradeRepo->getClassGrades($subject['id'], $sem, $academicYear);
                $subjectStats[] = [
                    'subject'       => $subject,
                    'semester'      => $sem,
                    'student_count' => count($grades),
                    'average'       => !empty($grades)
                        ? round(array_sum(array_column($grades, 'quarterly_grade')) / count($grades), 2)
                        : 0,
                ];
            }
        }

        return [
            'teacher'        => $teacher,
            'subjects'       => $subjects,
            'schedules'      => $schedules,
            'subject_stats'  => $subjectStats,
        ];
    }

    public function getStudentReportCard(int $studentId, int $semester, string $academicYear): array {
        $student = $this->studentRepo->findById($studentId);
        $grades  = $this->gradeRepo->getStudentGrades($studentId, $semester, $academicYear);

        $generalAverage = !empty($grades)
            ? round(array_sum(array_column($grades, 'quarterly_grade')) / count($grades), 2)
            : 0;

        return [
            'student'          => $student,
            'semester'         => $semester,
            'academic_year'    => $academicYear,
            'grades'           => $grades,
            'general_average'  => $generalAverage,
            'remarks'          => $this->getRemarksFromAverage($generalAverage),
        ];
    }

    private function getRemarksFromAverage(float $average): string {
        if ($average >= 90) return 'Outstanding';
        if ($average >= 85) return 'Very Satisfactory';
        if ($average >= 80) return 'Satisfactory';
        if ($average >= 75) return 'Fairly Satisfactory';
        return 'Did Not Meet Expectations';
    }
}