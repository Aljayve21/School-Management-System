<?php

class Request {
    private array $get;
    private array $post;
    private array $server;
    private array $files;

    public function __construct() {
        $this->get    = $_GET;
        $this->post   = $_POST;
        $this->server = $_SERVER;
        $this->files  = $_FILES;
    }

    public function input(string $key, $default = null) {
        return $this->post[$key] ?? $this->get[$key] ?? $default;
    }

    public function query(string $key, $default = null) {
        return $this->get[$key] ?? $default;
    }

    public function method(): string {
        return strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string {
        $uri = $this->server['REQUEST_URI'] ?? '/';
        $uri = strtok($uri, '?');
        $scriptDir = rtrim(dirname($this->server['SCRIPT_NAME'] ?? ''), '/');
        if ($scriptDir !== '' && strpos($uri, $scriptDir) === 0) {
            $uri = substr($uri, strlen($scriptDir));
        }
        $uri = rtrim($uri, '/');
        return $uri === '' ? '/' : $uri;
    }

    public function file(string $key) {
        return $this->files[$key] ?? null;
    }

    public function isPost(): bool {
        return $this->method() === 'POST';
    }

    public function all(): array {
        return $this->post;
    }

    public function has(string $key): bool {
        return isset($this->post[$key]) || isset($this->get[$key]);
    }
}