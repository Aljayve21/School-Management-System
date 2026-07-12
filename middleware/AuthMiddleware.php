<?php

class AuthMiddleware extends Middleware {
    private Session $session;

    public function __construct() {
        $this->session = new Session();
    }

    public function handle(Request $request, callable $next): void {
        if (!$this->session->isLoggedIn()) {
            Response::redirect(url('login'));
            return;
        }
        $next($request);
    }
}