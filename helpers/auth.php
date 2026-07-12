<?php

function isLoggedIn(): bool {
    return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
}

function currentUserId(): ?int {
    return $_SESSION['user_id'] ?? null;
}

function currentUserRole(): ?string {
    return $_SESSION['role'] ?? null;
}

function currentUserName(): ?string {
    return $_SESSION['user_name'] ?? null;
}

function isAdmin(): bool {
    return currentUserRole() === 'admin';
}

function isTeacher(): bool {
    return currentUserRole() === 'teacher';
}

function isStudent(): bool {
    return currentUserRole() === 'student';
}

function requireAuth(string ...$roles): void {
    if (!isLoggedIn()) {
        redirect('login');
    }
    if (!empty($roles) && !in_array(currentUserRole(), $roles)) {
        redirect('login');
    }
}

function requireAdmin(): void {
    requireAuth('admin');
}

function requireTeacher(): void {
    requireAuth('teacher');
}

function requireStudent(): void {
    requireAuth('student');
}