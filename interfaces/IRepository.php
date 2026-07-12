<?php

interface IRepository {
    public function findById(int $id): ?array;
    public function findAll(array $conditions = [], string $orderBy = 'id DESC', int $limit = 0): array;
    public function create(array $data): int;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function count(array $conditions = []): int;
}