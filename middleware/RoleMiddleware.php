<?php

class RoleMiddleware extends Middleware {
    private Session $session;
    private array $allowedRoles;

    public function __construct(array $allowedRoles = []) {
        $this->session      = new Session();
        $this->allowedRoles = $allowedRoles;
    }

    public function handle(Request $request, callable $next): void {
        if (!$this->session->isLoggedIn()) {
            Response::redirect(url('login'));
            return;
        }
        if (!empty($this->allowedRoles)) {
            $role = $this->session->userRole();
            if (!in_array($role, $this->allowedRoles)) {
                Response::error('Unauthorized access', 403);
                return;
            }
        }
        $next($request);
    }
}