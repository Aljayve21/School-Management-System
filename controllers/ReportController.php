<?php

class ReportController extends Controller {
    private ReportService $reportService;

    public function __construct() {
        parent::__construct();
        $this->reportService = new ReportService();
    }

    public function index(): void {
        $this->authorize('admin');
        $stats    = [];
        $subjects = [];
        $students = [];
        $teachers = [];
        try {
            $stats    = $this->reportService->getDashboardStats();
            $subjects = (new SubjectService())->getAll();
            $students = (new StudentService())->getAll();
            $teachers = (new TeacherService())->getAll();
        } catch (\Exception $e) {}
        $this->view('admin/reports.php', [
            'title'       => 'Reports',
            'stats'       => $stats,
            'subjects'    => $subjects,
            'students'    => $students,
            'teachers'    => $teachers,
            'currentPage' => 'reports',
        ]);
    }

    public function generate(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $type = $this->input('report_type', '');

        switch ($type) {
            case 'student':
                $studentId   = (int) $this->input('student_id');
                $academicYear = $this->input('academic_year', date('Y'));
                $report = $this->reportService->generateStudentReport($studentId, $academicYear);
                $this->success('Report generated', $report);
                break;

            case 'class':
                $subjectId   = (int) $this->input('subject_id');
                $semester    = (int) $this->input('semester', 1);
                $academicYear = $this->input('academic_year', date('Y'));
                $report = $this->reportService->generateClassReport($subjectId, $semester, $academicYear);
                $this->success('Report generated', $report);
                break;

            case 'teacher':
                $teacherId   = (int) $this->input('teacher_id');
                $academicYear = $this->input('academic_year', date('Y'));
                $report = $this->reportService->generateTeacherReport($teacherId, $academicYear);
                $this->success('Report generated', $report);
                break;

            case 'dashboard':
                $this->success('Dashboard stats', $this->reportService->getDashboardStats());
                break;

            default:
                $this->error('Invalid report type');
        }
    }
}