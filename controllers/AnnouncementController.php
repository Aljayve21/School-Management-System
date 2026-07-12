<?php

class AnnouncementController extends Controller {
    private AnnouncementService $announcementService;

    public function __construct() {
        parent::__construct();
        $this->announcementService = new AnnouncementService();
    }

    public function index(): void {
        $this->authorize('admin');
        $announcements = [];
        try {
            $announcements = $this->announcementService->getAll();
        } catch (\Exception $e) {}
        $this->view('admin/announcements.php', [
            'title'         => 'Manage Announcements',
            'announcements' => $announcements,
            'currentPage'   => 'announcements',
        ]);
    }

    public function store(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $v = new Validator($this->request->all());
        $v->required('title', 'Title')
          ->required('content', 'Content')
          ->required('target_role', 'Target Audience');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        $data = [
            'title'            => $this->input('title'),
            'content'          => $this->input('content'),
            'target_role'      => $this->input('target_role'),
            'target_grade_level' => $this->input('target_grade_level') ? (int) $this->input('target_grade_level') : null,
            'priority'         => $this->input('priority', 'medium'),
            'is_active'        => 1,
        ];
        $this->announcementService->create($data, $this->session->userId());
        $this->success('Announcement posted successfully');
    }

    public function update(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid announcement ID'); return; }

        $v = new Validator($this->request->all());
        $v->required('title', 'Title')
          ->required('content', 'Content');

        if ($v->fails()) { $this->error($v->firstError()); return; }

        $data = [
            'title'            => $this->input('title'),
            'content'          => $this->input('content'),
            'target_role'      => $this->input('target_role', 'all'),
            'target_grade_level' => $this->input('target_grade_level') ? (int) $this->input('target_grade_level') : null,
            'priority'         => $this->input('priority', 'medium'),
        ];
        $this->announcementService->update($id, $data, $this->session->userId());
        $this->success('Announcement updated successfully');
    }

    public function destroy(): void {
        $this->authorize('admin');
        if (!$this->request->isPost()) { $this->error('Invalid method', 405); return; }

        $id = (int) $this->input('id');
        if (!$id) { $this->error('Invalid announcement ID'); return; }

        $this->announcementService->delete($id, $this->session->userId());
        $this->success('Announcement deleted successfully');
    }

    public function teacherAnnouncements(): void {
        $this->authorize('teacher');
        $announcements = [];
        try {
            $announcements = $this->announcementService->getForRole('teacher');
        } catch (\Exception $e) {}
        $this->view('teacher/announcements.php', [
            'title'         => 'Announcements',
            'announcements' => $announcements,
            'currentPage'   => 'announcements',
        ]);
    }

    public function studentAnnouncements(): void {
        $this->authorize('student');
        $announcements = [];
        try {
            $announcements = $this->announcementService->getForRole('student');
        } catch (\Exception $e) {}
        $this->view('student/announcements.php', [
            'title'         => 'Announcements',
            'announcements' => $announcements,
            'currentPage'   => 'announcements',
        ]);
    }
}