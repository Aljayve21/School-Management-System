<?php

class TeacherService {
    private TeacherRepository $teacherRepo;
    private UserRepository $userRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->teacherRepo = new TeacherRepository();
        $this->userRepo    = new UserRepository();
        $this->logRepo     = new ActivityLogRepository();
    }

    public function getAll(): array {
        return $this->teacherRepo->findAll([], 'last_name, first_name');
    }

    public function getById(int $id): ?array {
        return $this->teacherRepo->findById($id);
    }

    public function getByDepartment(string $department): array {
        return $this->teacherRepo->getByDepartment($department);
    }

    public function getSubjects(int $teacherId): array {
        $repo = new SubjectRepository();
        return $repo->getTeacherSubjects($teacherId);
    }

    public function create(array $data, int $userId): int {
        $teacherId = $this->teacherRepo->create($data);
        $this->logRepo->logActivity($userId, 'Created teacher', 'teacher', $teacherId, null, $data);
        return $teacherId;
    }

    public function update(int $id, array $data, int $userId): bool {
        $old = $this->teacherRepo->findById($id);
        $result = $this->teacherRepo->update($id, $data);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Updated teacher', 'teacher', $id, $old, $data);
        }
        return $result;
    }

    public function delete(int $id, int $userId): bool {
        $old = $this->teacherRepo->findById($id);
        $teacherUserId = $old['user_id'] ?? null;
        $result = $this->teacherRepo->delete($id);
        if ($result && $teacherUserId) {
            $this->userRepo->delete($teacherUserId);
            $this->logRepo->logActivity($userId, 'Deleted teacher', 'teacher', $id, $old);
        }
        return $result;
    }

    public function getTotalCount(): int {
        return $this->teacherRepo->getTotalCount();
    }

    public function createWithUser(array $teacherData, string $email, string $password): int {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->userRepo->createWithProfile(
            ['email' => $email, 'password' => $hashedPassword, 'role' => 'teacher', 'status' => 'active'],
            'teachers',
            $teacherData
        );
        $this->logRepo->logActivity($userId, 'Teacher account created', 'teacher', $userId, null, $teacherData);
        return $userId;
    }
}