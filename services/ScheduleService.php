<?php

class ScheduleService {
    private ScheduleRepository $scheduleRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->scheduleRepo = new ScheduleRepository();
        $this->logRepo      = new ActivityLogRepository();
    }

    public function getAll(): array {
        return $this->scheduleRepo->findAll([], "FIELD(sc.day_of_week, 'Monday','Tuesday','Wednesday','Thursday','Friday'), sc.time_start");
    }

    public function getById(int $id): ?array {
        return $this->scheduleRepo->findById($id);
    }

    public function getByTeacher(int $teacherId): array {
        return $this->scheduleRepo->getByTeacher($teacherId);
    }

    public function getByStudent(int $gradeLevel, string $section): array {
        return $this->scheduleRepo->getByStudent($gradeLevel, $section);
    }

    public function getSections(): array {
        return $this->scheduleRepo->getSections();
    }

    public function hasConflict(int $teacherId, string $day, string $timeStart, string $timeEnd, int $exceptId = 0): bool {
        return $this->scheduleRepo->checkConflict($teacherId, $day, $timeStart, $timeEnd, $exceptId);
    }

    public function create(array $data, int $userId): int {
        if ($this->hasConflict($data['teacher_id'], $data['day_of_week'], $data['time_start'], $data['time_end'])) {
            throw new Exception("Schedule conflict: Teacher already has a class during this time.");
        }
        $id = $this->scheduleRepo->create($data);
        $this->logRepo->logActivity($userId, 'Created schedule', 'schedule', $id, null, $data);
        return $id;
    }

    public function update(int $id, array $data, int $userId): bool {
        if ($this->hasConflict($data['teacher_id'], $data['day_of_week'], $data['time_start'], $data['time_end'], $id)) {
            throw new Exception("Schedule conflict: Teacher already has a class during this time.");
        }
        $old = $this->scheduleRepo->findById($id);
        $result = $this->scheduleRepo->update($id, $data);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Updated schedule', 'schedule', $id, $old, $data);
        }
        return $result;
    }

    public function delete(int $id, int $userId): bool {
        $old = $this->scheduleRepo->findById($id);
        $result = $this->scheduleRepo->delete($id);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Deleted schedule', 'schedule', $id, $old);
        }
        return $result;
    }

    public function getTotalCount(): int {
        return $this->scheduleRepo->getTotalCount();
    }
}