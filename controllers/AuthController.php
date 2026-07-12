<?php

class AuthController extends Controller {
    private AuthService $authService;

    public function __construct() {
        parent::__construct();
        $this->authService = new AuthService();
    }

    public function showLogin(): void {
        if ($this->session->isLoggedIn()) {
            $this->redirectBasedOnRole();
            return;
        }
        $this->view('auth/login.php', ['title' => 'Login']);
    }

    public function login(): void {
        if (!$this->request->isPost()) {
            $this->error('Invalid request method', 405);
            return;
        }

        $email    = trim($this->input('email', ''));
        $password = $this->input('password', '');

        $v = new Validator($this->request->all());
        $v->required('email', 'Email')
          ->email('email', 'Email')
          ->required('password', 'Password');

        if ($v->fails()) {
            $this->error($v->firstError());
            return;
        }

        $user = $this->authService->login($email, $password);
        if (!$user) {
            $this->error('Invalid email or password');
            return;
        }

        $this->success('Login successful', ['role' => $user['role']]);
    }

    public function logout(): void {
        $this->authService->logout();
        $this->redirect(url('login'));
    }

    private function redirectBasedOnRole(): void {
        $role = $this->session->userRole();
        $this->redirect(url($role === 'admin' ? 'admin/dashboard' : $role . '/dashboard'));
    }
}