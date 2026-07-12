<?php

class Validator {
    private array $errors = [];
    private array $data;

    public function __construct(array $data) {
        $this->data = $data;
    }

    public function required(string $field, string $label = ''): self {
        $label = $label ?: $field;
        if (!isset($this->data[$field]) || trim($this->data[$field]) === '') {
            $this->errors[$field] = "{$label} is required.";
        }
        return $this;
    }

    public function email(string $field, string $label = ''): self {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "{$label} must be a valid email.";
        }
        return $this;
    }

    public function minLength(string $field, int $min, string $label = ''): self {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "{$label} must be at least {$min} characters.";
        }
        return $this;
    }

    public function maxLength(string $field, int $max, string $label = ''): self {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "{$label} must not exceed {$max} characters.";
        }
        return $this;
    }

    public function numeric(string $field, string $label = ''): self {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "{$label} must be a number.";
        }
        return $this;
    }

    public function in(string $field, array $allowed, string $label = ''): self {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && !in_array($this->data[$field], $allowed)) {
            $this->errors[$field] = "{$label} is not valid.";
        }
        return $this;
    }

    public function matches(string $field, string $field2, string $label = ''): self {
        $label = $label ?: $field;
        if (isset($this->data[$field]) && ($this->data[$field] ?? '') !== ($this->data[$field2] ?? '')) {
            $this->errors[$field] = "{$label} does not match.";
        }
        return $this;
    }

    public function unique(string $field, string $table, string $column = '', int $exceptId = 0, string $label = ''): self {
        $label  = $label ?: $field;
        $column = $column ?: $field;
        if (isset($this->data[$field])) {
            $db = Database::getInstance()->getConnection();
            $sql = "SELECT COUNT(*) as c FROM {$table} WHERE {$column} = ?";
            $params = [$this->data[$field]];
            if ($exceptId > 0) {
                $sql .= " AND id != ?";
                $params[] = $exceptId;
            }
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            if ((int) $stmt->fetch()['c'] > 0) {
                $this->errors[$field] = "{$label} is already taken.";
            }
        }
        return $this;
    }

    public function fails(): bool {
        return !empty($this->errors);
    }

    public function passes(): bool {
        return empty($this->errors);
    }

    public function errors(): array {
        return $this->errors;
    }

    public function firstError(): ?string {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
}