<?php

class ScheduleController extends Controller {
    private ScheduleService $scheduleService;

    public function __construct() {
        parent::__construct();
        $this->scheduleService = new ScheduleService();
    }

    public function index(): void {
        $this->authorize('admin');
        $schedules = [];
        $subjects  = [];
        $teachers  = [];
        $sections  = [];
        try {
            $schedules = $this->scheduleService->getAll();
            $subjects  = (new SubjectService())->getActive();
            $teachers  = (new TeacherService())->getAll();
            $sections  = $this->scheduleService->getSections();
        } catch (\Exception $e) {}
        $this->view('admin/schedules.php', [
            'title'       => 'Class Schedules',
            'schedules'   => $schedules,
            'subjects'    => $subjects,
            'teachers'    => $teachers,
            'sections'    => $sections,
            'currentPage' => 'schedules',
        ]);
    }

    public function store(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $v = new Validator($this->request->all());
        $v->required('subject_id', 'Subject')
          ->required('teacher_id', 'Teacher')
          ->required('grade_level', 'Grade Level')
          ->required('section', 'Section')
          ->required('day_of_week', 'Day')
          ->required('time_start', 'Start Time')
          ->required('time_end', 'End Time');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $data = [
                'subject_id'  => (int) $this->input('subject_id'),
                'teacher_id'  => (int) $this->input('teacher_id'),
                'grade_level' => (int) $this->input('grade_level'),
                'section'     => $this->input('section'),
                'day_of_week' => $this->input('day_of_week'),
                'time_start'  => $this->input('time_start'),
                'time_end'    => $this->input('time_end'),
                'room'        => $this->input('room', ''),
            ];
            $this->scheduleService->create($data, $this->session->userId());
            $this->success('Schedule created successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function update(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid schedule ID'); return; }

        try {
            $data = [
                'subject_id'  => (int) $this->input('subject_id'),
                'teacher_id'  => (int) $this->input('teacher_id'),
                'grade_level' => (int) $this->input('grade_level'),
                'section'     => $this->input('section'),
                'day_of_week' => $this->input('day_of_week'),
                'time_start'  => $this->input('time_start'),
                'time_end'    => $this->input('time_end'),
                'room'        => $this->input('room', ''),
            ];
            $this->scheduleService->update($id, $data, $this->session->userId());
            $this->success('Schedule updated successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function destroy(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid schedule ID'); return; }

        $this->scheduleService->delete($id, $this->session->userId());
        $this->success('Schedule deleted successfully');
    }

    public function teacherClasses(): void {
        $this->authorize('teacher');
        $userId    = $this->session->userId();
        $teacher   = null;
        $schedules = [];
        $subjects  = [];
        try {
            $teacher   = (new TeacherRepository())->findByUserId($userId);
            $schedules = $teacher ? $this->scheduleService->getByTeacher($teacher['id']) : [];
            $subjects  = $teacher ? (new SubjectService())->getTeacherSubjects($teacher['id']) : [];
        } catch (\Exception $e) {}
        $this->view('teacher/classes.php', [
            'title'       => 'My Classes',
            'schedules'   => $schedules,
            'subjects'    => $subjects,
            'teacher'     => $teacher,
            'currentPage' => 'classes',
        ]);
    }

    public function studentSchedule(): void {
        $this->authorize('student');
        $userId    = $this->session->userId();
        $student   = null;
        $schedules = [];
        try {
            $student   = (new StudentRepository())->findByUserId($userId);
            $schedules = $student
                ? $this->scheduleService->getByStudent($student['grade_level'], $student['section'])
                : [];
        } catch (\Exception $e) {}
        $this->view('student/schedule.php', [
            'title'       => 'My Schedule',
            'schedules'   => $schedules,
            'student'     => $student,
            'currentPage' => 'schedule',
        ]);
    }
}