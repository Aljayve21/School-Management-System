<?php

interface IAuthenticatable {
    public function login(string $email, string $password): ?array;
    public function logout(): void;
    public function getCurrentUser(): ?array;
    public function isAuthenticated(): bool;
}