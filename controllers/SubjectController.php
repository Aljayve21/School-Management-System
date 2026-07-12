<?php

class SubjectController extends Controller {
    private SubjectService $subjectService;

    public function __construct() {
        parent::__construct();
        $this->subjectService = new SubjectService();
    }

    public function index(): void {
        $this->authorize('admin');
        $subjects  = [];
        $teachers  = [];
        try {
            $subjects  = $this->subjectService->getAll();
            $teachers  = (new TeacherService())->getAll();
        } catch (\Exception $e) {}
        $this->view('admin/subjects.php', [
            'title'       => 'Manage Subjects',
            'subjects'    => $subjects,
            'teachers'    => $teachers,
            'currentPage' => 'subjects',
        ]);
    }

    public function store(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $v = new Validator($this->request->all());
        $v->required('subject_code', 'Subject Code')
          ->required('subject_name', 'Subject Name')
          ->required('grade_level', 'Grade Level')
          ->unique('subject_code', 'subjects', 'subject_code', 0, 'Subject Code');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        $data = [
            'subject_code' => $this->input('subject_code'),
            'subject_name' => $this->input('subject_name'),
            'description'  => $this->input('description', ''),
            'grade_level'  => (int) $this->input('grade_level'),
            'credits'      => (int) $this->input('credits', 1),
            'status'       => 'active',
        ];
        $this->subjectService->create($data, $this->session->userId());
        $this->success('Subject created successfully');
    }

    public function update(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid subject ID'); return; }

        $v = new Validator($this->request->all());
        $v->required('subject_name', 'Subject Name')
          ->required('grade_level', 'Grade Level');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        $data = [
            'subject_name' => $this->input('subject_name'),
            'description'  => $this->input('description', ''),
            'grade_level'  => (int) $this->input('grade_level'),
            'credits'      => (int) $this->input('credits', 1),
            'status'       => $this->input('status', 'active'),
        ];
        $this->subjectService->update($id, $data, $this->session->userId());
        $this->success('Subject updated successfully');
    }

    public function destroy(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid subject ID'); return; }

        $this->subjectService->delete($id, $this->session->userId());
        $this->success('Subject deleted successfully');
    }
}