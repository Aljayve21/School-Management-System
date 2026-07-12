<?php

class SubjectService {
    private SubjectRepository $subjectRepo;
    private ActivityLogRepository $logRepo;

    public function __construct() {
        $this->subjectRepo = new SubjectRepository();
        $this->logRepo     = new ActivityLogRepository();
    }

    public function getAll(): array {
        return $this->subjectRepo->findAll([], 'subject_name');
    }

    public function getById(int $id): ?array {
        return $this->subjectRepo->findById($id);
    }

    public function getActive(): array {
        return $this->subjectRepo->getActive();
    }

    public function getByGradeLevel(int $gradeLevel): array {
        return $this->subjectRepo->getByGradeLevel($gradeLevel);
    }

    public function getTeacherSubjects(int $teacherId): array {
        return $this->subjectRepo->getTeacherSubjects($teacherId);
    }

    public function create(array $data, int $userId): int {
        $id = $this->subjectRepo->create($data);
        $this->logRepo->logActivity($userId, 'Created subject', 'subject', $id, null, $data);
        return $id;
    }

    public function update(int $id, array $data, int $userId): bool {
        $old = $this->subjectRepo->findById($id);
        $result = $this->subjectRepo->update($id, $data);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Updated subject', 'subject', $id, $old, $data);
        }
        return $result;
    }

    public function delete(int $id, int $userId): bool {
        $old = $this->subjectRepo->findById($id);
        $result = $this->subjectRepo->delete($id);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Deleted subject', 'subject', $id, $old);
        }
        return $result;
    }

    public function assignTeacher(int $subjectId, int $teacherId, int $userId): bool {
        $result = $this->subjectRepo->assignTeacher($subjectId, $teacherId);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Assigned teacher to subject', 'subject', $subjectId, null, ['teacher_id' => $teacherId]);
        }
        return $result;
    }

    public function removeTeacher(int $subjectId, int $teacherId, int $userId): bool {
        $result = $this->subjectRepo->removeTeacher($subjectId, $teacherId);
        if ($result) {
            $this->logRepo->logActivity($userId, 'Removed teacher from subject', 'subject', $subjectId, ['teacher_id' => $teacherId]);
        }
        return $result;
    }

    public function getTotalCount(): int {
        return $this->subjectRepo->getTotalCount();
    }
}