<?php

class ActivityLogMiddleware extends Middleware {
    private ActivityLogService $logService;

    public function __construct() {
        $this->logService = new ActivityLogService();
    }

    public function handle(Request $request, callable $next): void {
        $next($request);

        $session = new Session();
        $userId  = $session->userId();
        if ($userId && $request->isPost()) {
            $this->logService->log(
                $userId,
                'Page access: ' . $request->uri(),
                null, null, null,
                ['method' => $request->method(), 'uri' => $request->uri()]
            );
        }
    }
}