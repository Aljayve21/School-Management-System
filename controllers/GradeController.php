<?php

class GradeController extends Controller {
    private GradeService $gradeService;

    public function __construct() {
        parent::__construct();
        $this->gradeService = new GradeService();
    }

    public function index(): void {
        $this->authorize('admin');
        $semesters = [];
        $subjects  = [];
        $students  = [];
        try {
            $semesters = $this->gradeService->getSemesters();
            $subjects  = (new SubjectService())->getActive();
            $students  = (new StudentService())->getAll();
        } catch (\Exception $e) {}
        $this->view('admin/grades.php', [
            'title'       => 'Manage Grades',
            'semesters'   => $semesters,
            'subjects'    => $subjects,
            'students'    => $students,
            'currentPage' => 'grades',
        ]);
    }

    public function store(): void {
        $this->authorize('admin', 'teacher');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $v = new Validator($this->request->all());
        $v->required('student_id', 'Student')
          ->required('subject_id', 'Subject')
          ->required('semester', 'Semester')
          ->required('academic_year', 'Academic Year')
          ->required('written_work', 'Written Work')
          ->required('performance_task', 'Performance Task')
          ->required('quarterly_exam', 'Quarterly Exam');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $teacherId = null;
            if ($this->session->userRole() === 'teacher') {
                $teacher = (new TeacherRepository())->findByUserId($this->session->userId());
                $teacherId = $teacher ? $teacher['id'] : null;
            } else {
                $subject = (new SubjectRepository())->findById((int) $this->input('subject_id'));
                $teacherId = $subject ? ($subject['teacher_id'] ?? null) : null;
            }

            $data = [
                'student_id'       => (int) $this->input('student_id'),
                'subject_id'       => (int) $this->input('subject_id'),
                'teacher_id'       => $teacherId,
                'semester'         => (int) $this->input('semester'),
                'academic_year'    => $this->input('academic_year'),
                'written_work'     => (float) $this->input('written_work'),
                'performance_task' => (float) $this->input('performance_task'),
                'quarterly_exam'   => (float) $this->input('quarterly_exam'),
            ];
            $this->gradeService->create($data, $this->session->userId());
            $this->success('Grade saved successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function update(): void {
        $this->authorize('admin', 'teacher');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid grade ID'); return; }

        $v = new Validator($this->request->all());
        $v->required('written_work', 'Written Work')
          ->required('performance_task', 'Performance Task')
          ->required('quarterly_exam', 'Quarterly Exam');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $data = [
                'written_work'     => (float) $this->input('written_work'),
                'performance_task' => (float) $this->input('performance_task'),
                'quarterly_exam'   => (float) $this->input('quarterly_exam'),
            ];
            $this->gradeService->update($id, $data, $this->session->userId());
            $this->success('Grade updated successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function teacherGrades(): void {
        $this->authorize('teacher');
        $userId   = $this->session->userId();
        $teacher  = null;
        $subjects = [];
        $semesters = [];
        try {
            $teacher  = (new TeacherRepository())->findByUserId($userId);
            $subjects = $teacher ? (new SubjectService())->getTeacherSubjects($teacher['id']) : [];
            $semesters = $this->gradeService->getSemesters();
        } catch (\Exception $e) {}
        $this->view('teacher/grades.php', [
            'title'       => 'Input Grades',
            'subjects'    => $subjects,
            'teacher'     => $teacher,
            'semesters'   => $semesters,
            'currentPage' => 'grades',
        ]);
    }

    public function studentGrades(): void {
        $this->authorize('student');
        $userId          = $this->session->userId();
        $student         = null;
        $grades          = [];
        $generalAverage  = 0;
        $currentYear     = date('Y');
        $currentSem      = date('n') <= 6 ? 2 : 1;
        try {
            $student = (new StudentRepository())->findByUserId($userId);
            if ($student) {
                $grades         = $this->gradeService->getStudentGrades($student['id'], $currentSem, $currentYear);
                $generalAverage = $this->gradeService->getGeneralAverage($student['id'], $currentSem, $currentYear);
            }
        } catch (\Exception $e) {}
        $this->view('student/grades.php', [
            'title'            => 'My Grades',
            'student'          => $student,
            'grades'           => $grades,
            'general_average'  => $generalAverage,
            'semester'         => $currentSem,
            'academic_year'    => $currentYear,
            'currentPage'      => 'grades',
        ]);
    }
}