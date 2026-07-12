<?php

class StudentService {
    private StudentRepository $studentRepo;
    private UserRepository $userRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->studentRepo = new StudentRepository();
        $this->userRepo    = new UserRepository();
        $this->logRepo     = new ActivityLogRepository();
    }

    public function getAll(): array {
        return $this->studentRepo->findAll([], 'last_name, first_name');
    }

    public function getById(int $id): ?array {
        return $this->studentRepo->findById($id);
    }

    public function getByGradeLevel(int $gradeLevel): array {
        return $this->studentRepo->getByGradeLevel($gradeLevel);
    }

    public function getBySection(int $gradeLevel, string $section): array {
        return $this->studentRepo->getBySection($gradeLevel, $section);
    }

    public function getGradeSections(): array {
        return $this->studentRepo->getGradeSections();
    }

    public function create(array $data, int $userId): int {
        $studentId = $this->studentRepo->create($data);
        $this->logRepo->logActivity($userId, 'Created student', 'student', $studentId, null, $data);
        return $studentId;
    }

    public function update(int $id, array $data, int $userId): bool {
        $old = $this->studentRepo->findById($id);
        $result = $this->studentRepo->update($id, $data);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Updated student', 'student', $id, $old, $data);
        }
        return $result;
    }

    public function delete(int $id, int $userId): bool {
        $old = $this->studentRepo->findById($id);
        $studentUserId = $old['user_id'] ?? null;
        $result = $this->studentRepo->delete($id);
        if ($result && $studentUserId) {
            $this->userRepo->delete($studentUserId);
            $this->logRepo->logActivity($userId, 'Deleted student', 'student', $id, $old);
        }
        return $result;
    }

    public function getTotalCount(): int {
        return $this->studentRepo->getTotalCount();
    }

    public function getCountByGrade(int $gradeLevel): int {
        return $this->studentRepo->getCountByGrade($gradeLevel);
    }

    public function createWithUser(array $studentData, string $email, string $password): int {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepo->createWithProfile(
            ['email' => $email, 'password' => $hashedPassword, 'role' => 'student', 'status' => 'active'],
            'students',
            $studentData
        );
        $this->logRepo->logActivity($userId, 'Student account created', 'student', $userId, null, $studentData);
        return $userId;
    }
}