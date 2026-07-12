<?php

class AnnouncementService {
    private AnnouncementRepository $announcementRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->announcementRepo = new AnnouncementRepository();
        $this->logRepo          = new ActivityLogRepository();
    }

    public function getAll(): array {
        return $this->announcementRepo->findAll([], 'a.created_at DESC');
    }

    public function getById(int $id): ?array {
        return (new Announcement())->findById($id);
    }

    public function getActive(): array {
        return $this->announcementRepo->getActive();
    }

    public function getForRole(string $role): array {
        return $this->announcementRepo->getForRole($role);
    }

    public function create(array $data, int $userId): int {
        $data['posted_by'] = $userId;
        $id = $this->announcementRepo->create($data);
        $this->logRepo->logActivity($userId, 'Created announcement', 'announcement', $id, null, $data);
        return $id;
    }

    public function update(int $id, array $data, int $userId): bool {
        $old = $this->announcementRepo->findById($id);
        $result = $this->announcementRepo->update($id, $data);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Updated announcement', 'announcement', $id, $old, $data);
        }
        return $result;
    }

    public function delete(int $id, int $userId): bool {
        $old = $this->announcementRepo->findById($id);
        $result = $this->announcementRepo->delete($id);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Deleted announcement', 'announcement', $id, $old);
        }
        return $result;
    }

    public function toggleActive(int $id, int $userId): bool {
        $old = $this->announcementRepo->findById($id);
        $result = $this->announcementRepo->toggleActive($id);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Toggled announcement status', 'announcement', $id, $old);
        }
        return $result;
    }

    public function getTotalCount(): int {
        return $this->announcementRepo->getTotalCount();
    }
}