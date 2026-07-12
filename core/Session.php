<?php

class Session {
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function get(string $key, $default = null) {
        return $_SESSION[$key] ?? $default;
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function has(string $key): bool {
        return isset($_SESSION[$key]);
    }

    public function remove(string $key): void {
        unset($_SESSION[$key]);
    }

    public function destroy(): void {
        session_destroy();
        $_SESSION = [];
    }

    public function flash(string $key, $value): void {
        $_SESSION['_flash'][$key] = $value;
    }

    public function getFlash(string $key, $default = null) {
        $value = $_SESSION['_flash'][$key] ?? $default;
        unset($_SESSION['_flash'][$key]);
        return $value;
    }

    public function isLoggedIn(): bool {
        return $this->get('auth') === true;
    }

    public function userId(): ?int {
        return $this->get('user_id');
    }

    public function userRole(): ?string {
        return $this->get('role');
    }

    public function userName(): ?string {
        return $this->get('user_name');
    }

    public function isAdmin(): bool {
        return $this->userRole() === 'admin';
    }

    public function isTeacher(): bool {
        return $this->userRole() === 'teacher';
    }

    public function isStudent(): bool {
        return $this->userRole() === 'student';
    }
}