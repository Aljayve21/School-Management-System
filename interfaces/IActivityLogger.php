<?php

interface IActivityLogger {
    public function log(int $userId, string $action, ?string $entityType = null, ?int $entityId = null, $oldValues = null, $newValues = null): void;
    public function getLogs(array $conditions = [], int $limit = 50): array;
    public function getLogsByUser(int $userId, int $limit = 50): array;
}