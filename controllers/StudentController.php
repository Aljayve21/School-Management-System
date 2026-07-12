<?php

class StudentController extends Controller {
    private StudentService $studentService;

    public function __construct() {
        parent::__construct();
        $this->studentService = new StudentService();
    }

    public function index(): void {
        $this->authorize('admin');
        $students = [];
        $sections = [];
        try {
            $students = $this->studentService->getAll();
            $sections = $this->studentService->getGradeSections();
        } catch (\Exception $e) {}
        $this->view('admin/students.php', [
            'title'       => 'Manage Students',
            'students'    => $students,
            'sections'    => $sections,
            'currentPage' => 'students',
        ]);
    }

    public function store(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $v = new Validator($this->request->all());
        $v->required('student_id', 'Student ID')
          ->required('first_name', 'First Name')
          ->required('last_name', 'Last Name')
          ->required('grade_level', 'Grade Level')
          ->required('email', 'Email')
          ->required('password', 'Password')
          ->email('email', 'Email')
          ->minLength('password', 6, 'Password')
          ->unique('student_id', 'students', 'student_id', 0, 'Student ID')
          ->unique('email', 'users', 'email', 0, 'Email');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $email    = $this->input('email');
            $password = $this->input('password');
            $data = [
                'student_id'      => $this->input('student_id'),
                'first_name'      => $this->input('first_name'),
                'last_name'       => $this->input('last_name'),
                'grade_level'     => (int) $this->input('grade_level'),
                'section'         => $this->input('section', ''),
                'date_of_birth'   => $this->input('date_of_birth', null),
                'gender'          => $this->input('gender', null),
                'guardian_name'   => $this->input('guardian_name', ''),
                'guardian_phone'  => $this->input('guardian_phone', ''),
                'address'         => $this->input('address', ''),
            ];
            $this->studentService->createWithUser($data, $email, $password);
            $this->success('Student created successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function update(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid student ID'); return; }

        $v = new Validator($this->request->all());
        $v->required('first_name', 'First Name')
          ->required('last_name', 'Last Name')
          ->required('grade_level', 'Grade Level');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $data = [
                'first_name'      => $this->input('first_name'),
                'last_name'       => $this->input('last_name'),
                'grade_level'     => (int) $this->input('grade_level'),
                'section'         => $this->input('section', ''),
                'guardian_name'   => $this->input('guardian_name', ''),
                'guardian_phone'  => $this->input('guardian_phone', ''),
                'address'         => $this->input('address', ''),
            ];
            $this->studentService->update($id, $data, $this->session->userId());
            $this->success('Student updated successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function destroy(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid student ID'); return; }

        $this->studentService->delete($id, $this->session->userId());
        $this->success('Student deleted successfully');
    }
}