<?php

class ActivityLogController extends Controller {
    private ActivityLogService $logService;

    public function __construct() {
        parent::__construct();
        $this->logService = new ActivityLogService();
    }

    public function index(): void {
        $this->authorize('admin');
        $userId   = $this->input('user_id');
        $logs     = [];
        $users    = [];
        try {
            $logs = $userId
                ? $this->logService->getLogsByUser((int) $userId, 100)
                : $this->logService->getLogs([], 100);
            $users = (new UserRepository())->findAll([], 'email');
        } catch (\Exception $e) {}
        $this->view('admin/activitylogs.php', [
            'title'       => 'Activity Logs',
            'logs'        => $logs,
            'users'       => $users,
            'filter_user' => $userId,
            'currentPage' => 'activity-logs',
        ]);
    }
}