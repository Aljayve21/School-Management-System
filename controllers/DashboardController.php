<?php

class DashboardController extends Controller {
    private ReportService $reportService;
    private AnnouncementService $announcementService;
    private GradeService $gradeService;

    public function __construct() {
        parent::__construct();
        $this->reportService      = new ReportService();
        $this->announcementService = new AnnouncementService();
        $this->gradeService       = new GradeService();
    }

    public function index(): void {
        $this->authorize('admin');
        $stats = [];
        $announcements = [];
        try {
            $stats = $this->reportService->getDashboardStats();
            $announcements = $this->announcementService->getAll();
        } catch (\Exception $e) {}
        $this->view('admin/dashboard.php', [
            'title'         => 'Admin Dashboard',
            'stats'         => $stats,
            'announcements' => $announcements,
            'currentPage'   => 'dashboard',
        ]);
    }

    public function teacherDashboard(): void {
        $this->authorize('teacher');
        $userId = $this->session->userId();
        $teacher = null;
        $subjects = [];
        $schedules = [];
        $announcements = [];
        try {
            $teacherRepo = new TeacherRepository();
            $teacher = $teacherRepo->findByUserId($userId);
            if ($teacher) {
                $subjectRepo = new SubjectRepository();
                $subjects = $subjectRepo->getTeacherSubjects($teacher['id']);
                $scheduleRepo = new ScheduleRepository();
                $schedules = $scheduleRepo->getByTeacher($teacher['id']);
            }
            $announcements = $this->announcementService->getForRole('teacher');
        } catch (\Exception $e) {}
        $this->view('teacher/dashboard.php', [
            'title'         => 'Teacher Dashboard',
            'teacher'       => $teacher,
            'subjects'      => $subjects,
            'schedules'     => $schedules,
            'announcements' => $announcements,
            'currentPage'   => 'dashboard',
        ]);
    }

    public function studentDashboard(): void {
        $this->authorize('student');
        $userId = $this->session->userId();
        $student = null;
        $grades = [];
        $schedules = [];
        $announcements = [];
        try {
            $studentRepo = new StudentRepository();
            $student = $studentRepo->findByUserId($userId);
            if ($student) {
                $currentYear = date('Y');
                $currentSem  = date('n') <= 6 ? 2 : 1;
                $grades = $this->gradeService->getStudentGrades($student['id'], $currentSem, $currentYear);
                $scheduleRepo = new ScheduleRepository();
                $schedules = $scheduleRepo->getByStudent($student['grade_level'], $student['section']);
            }
            $announcements = $this->announcementService->getForRole('student');
        } catch (\Exception $e) {}
        $this->view('student/dashboard.php', [
            'title'         => 'Student Dashboard',
            'student'       => $student,
            'grades'        => $grades,
            'schedules'     => $schedules,
            'announcements' => $announcements,
            'currentPage'   => 'dashboard',
        ]);
    }
}