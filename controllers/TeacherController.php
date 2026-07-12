<?php

class TeacherController extends Controller {
    private TeacherService $teacherService;

    public function __construct() {
        parent::__construct();
        $this->teacherService = new TeacherService();
    }

    public function index(): void {
        $this->authorize('admin');
        $teachers = [];
        try {
            $teachers = $this->teacherService->getAll();
        } catch (\Exception $e) {}
        $this->view('admin/teachers.php', [
            'title'       => 'Manage Teachers',
            'teachers'    => $teachers,
            'currentPage' => 'teachers',
        ]);
    }

    public function store(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $v = new Validator($this->request->all());
        $v->required('teacher_id', 'Teacher ID')
          ->required('first_name', 'First Name')
          ->required('last_name', 'Last Name')
          ->required('email', 'Email')
          ->required('password', 'Password')
          ->email('email', 'Email')
          ->minLength('password', 6, 'Password')
          ->unique('teacher_id', 'teachers', 'teacher_id', 0, 'Teacher ID')
          ->unique('email', 'users', 'email', 0, 'Email');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $data = [
                'teacher_id'     => $this->input('teacher_id'),
                'first_name'     => $this->input('first_name'),
                'last_name'      => $this->input('last_name'),
                'department'     => $this->input('department', ''),
                'specialization' => $this->input('specialization', ''),
                'phone'          => $this->input('phone', ''),
                'address'        => $this->input('address', ''),
            ];
            $this->teacherService->createWithUser($data, $this->input('email'), $this->input('password'));
            $this->success('Teacher created successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function update(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid teacher ID'); return; }

        $v = new Validator($this->request->all());
        $v->required('first_name', 'First Name')
          ->required('last_name', 'Last Name');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        try {
            $data = [
                'first_name'     => $this->input('first_name'),
                'last_name'      => $this->input('last_name'),
                'department'     => $this->input('department', ''),
                'specialization' => $this->input('specialization', ''),
                'phone'          => $this->input('phone', ''),
                'address'        => $this->input('address', ''),
            ];
            $this->teacherService->update($id, $data, $this->session->userId());
            $this->success('Teacher updated successfully');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function destroy(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid teacher ID'); return; }

        $this->teacherService->delete($id, $this->session->userId());
        $this->success('Teacher deleted successfully');
    }
}