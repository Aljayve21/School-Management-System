<?php

class AuthService implements IAuthenticatable {
    private UserRepository $userRepo;
    private ActivityLogRepository $logRepo;
    private Session $session;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->logRepo  = new ActivityLogRepository();
        $this->session  = new Session();
    }

    public function login(string $email, string $password): ?array {
        $user = $this->userRepo->findByEmail($email);
        if (!$user) return null;
        if ($user['status'] !== 'active') return null;
        if (!password_verify($password, $user['password'])) return null;

        $this->session->set('auth', true);
        $this->session->set('user_id', $user['id']);
        $this->session->set('role', $user['role']);
        $this->session->set('user_name', $user['email']);

        $profile = $this->getUserProfile($user);
        if ($profile) {
            $this->session->set('user_name', $profile['first_name'] . ' ' . $profile['last_name']);
        }
        $this->session->set('profile', $profile);

        $this->logRepo->logActivity($user['id'], 'Logged in', 'user', $user['id']);
        return $user;
    }

    public function logout(): void {
        $userId = $this->session->userId();
        if ($userId) {
            $this->logRepo->logActivity($userId, 'Logged out', 'user', $userId);
        }
        $this->session->destroy();
    }

    public function getCurrentUser(): ?array {
        $userId = $this->session->userId();
        if (!$userId) return null;
        return $this->userRepo->getWithProfile($userId);
    }

    public function isAuthenticated(): bool {
        return $this->session->isLoggedIn();
    }

    public function register(array $userData, array $profileData, string $role): int {
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        $userData['role']     = $role;
        $userData['status']   = 'active';

        $profileTable = $role === 'teacher' ? 'teachers' : 'students';
        $userId = $this->userRepo->createWithProfile($userData, $profileTable, $profileData);
        $this->logRepo->logActivity($userId, 'Account registered', 'user', $userId, null, ['role' => $role]);
        return $userId;
    }

    public function changePassword(int $userId, string $oldPassword, string $newPassword): bool {
        $user = $this->userRepo->findById($userId);
        if (!$user || !password_verify($oldPassword, $user['password'])) return false;
        $this->userRepo->update($userId, ['password' => password_hash($newPassword, PASSWORD_DEFAULT)]);
        $this->logRepo->logActivity($userId, 'Password changed', 'user', $userId);
        return true;
    }

    private function getUserProfile(array $user): ?array {
        if ($user['role'] === 'teacher') {
            $repo = new TeacherRepository();
            return $repo->findByUserId($user['id']);
        } elseif ($user['role'] === 'student') {
            $repo = new StudentRepository();
            return $repo->findByUserId($user['id']);
        }
        return null;
    }
}