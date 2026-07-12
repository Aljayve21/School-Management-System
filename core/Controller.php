<?php

abstract class Controller {
    protected Session $session;
    protected Request $request;

    public function __construct() {
        $this->session = new Session();
        $this->request = new Request();
    }

    protected function view(string $path, array $data = []): void {
        $data['session'] = $this->session;
        Response::view($path, $data);
    }

    protected function json(array $data, int $code = 200): void {
        Response::json($data, $code);
    }

    protected function success(string $message = 'Success', $data = null): void {
        Response::success($message, $data);
    }

    protected function error(string $message = 'Error', int $code = 400): void {
        Response::error($message, $code);
    }

    protected function redirect(string $url): void {
        Response::redirect($url);
    }

    protected function input(string $key, $default = null) {
        return $this->request->input($key, $default);
    }

    protected function authorize(string ...$roles): void {
        if (!$this->session->isLoggedIn()) {
            $this->redirect(APP_URL . '/login');
            exit;
        }
        if (!empty($roles) && !in_array($this->session->userRole(), $roles)) {
            $this->error('Unauthorized', 403);
            exit;
        }
    }
}