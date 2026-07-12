<?php

class User extends Model {
    protected string $table = 'users';

    public function findByEmail(string $email): ?array {
        return $this->findWhere('email', $email);
    }

    public function createWithProfile(array $userData, array $profileData, string $profileTable): int {
        $this->db->beginTransaction();
        try {
            $userId = $this->create($userData);
            $profileData['user_id'] = $userId;
            $columns    = implode(', ', array_keys($profileData));
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

    public function getActiveUsers(): array {
        return $this->findAll(['status' => 'active']);
    }

    public function getUsersByRole(string $role): array {
        return $this->findAll(['role' => $role]);
    }

    public function updateStatus(int $id, string $status): bool {
        return $this->update($id, ['status' => $status]);
    }
}