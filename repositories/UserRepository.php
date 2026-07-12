<?php

class UserRepository extends Model implements IRepository {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findWhere('email', $email);
    }

    public function getWithProfile(int $id): ?array {
        $user = $this->findById($id);
        if (!$user) return null;

        if ($user['role'] === 'teacher') {
            $sql = "SELECT t.* FROM teachers t WHERE t.user_id = ?";
            $profile = $this->rawQuerySingle($sql, [$id]);
        } elseif ($user['role'] === 'student') {
            $sql = "SELECT s.* FROM students s WHERE s.user_id = ?";
            $profile = $this->rawQuerySingle($sql, [$id]);
        } else {
            $profile = null;
        }

        $user['profile'] = $profile;
        return $user;
    }

    public function createWithProfile(array $userData, string $profileTable, array $profileData): int {
        $this->db->beginTransaction();
        try {
            $userId = $this->create($userData);
            $profileData['user_id'] = $userId;
            $columns      = implode(', ', array_keys($profileData));
            $placeholders = implode(', ', array_fill(0, count($profileData), '?'));
            $this->db->prepare("INSERT INTO {$profileTable} ({$columns}) VALUES ({$placeholders})")
                     ->execute(array_values($profileData));
            $this->db->commit();
            return $userId;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function getTotalCount(): int {
        return $this->count();
    }

    public function getCountByRole(string $role): int {
        return $this->count(['role' => $role]);
    }
}