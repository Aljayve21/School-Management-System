<?php

function formatDate(string $date, string $format = 'M d, Y'): string {
    return date($format, strtotime($date));
}

function formatTime(string $time): string {
    return date('g:i A', strtotime($time));
}

function formatDateTime(string $datetime): string {
    return date('M d, Y g:i A', strtotime($datetime));
}

function timeAgo(string $datetime): string {
    $now  = new DateTime();
    $past = new DateTime($datetime);
    $diff = $now->diff($past);

    if ($diff->y > 0) return $diff->y . ' year' . ($diff->y > 1 ? 's' : '') . ' ago';
    if ($diff->m > 0) return $diff->m . ' month' . ($diff->m > 1 ? 's' : '') . ' ago';
    if ($diff->d > 0) return $diff->d . ' day' . ($diff->d > 1 ? 's' : '') . ' ago';
    if ($diff->h > 0) return $diff->h . ' hour' . ($diff->h > 1 ? 's' : '') . ' ago';
    if ($diff->i > 0) return $diff->i . ' minute' . ($diff->i > 1 ? 's' : '') . ' ago';
    return 'Just now';
}

function sanitize(string $input): string {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function generateId(string $prefix, int $number, int $length = 4): string {
    return $prefix . str_pad($number, $length, '0', STR_PAD_LEFT);
}

function gradeColor(float $grade): string {
    if ($grade >= 90) return 'success';
    if ($grade >= 80) return 'primary';
    if ($grade >= 75) return 'warning';
    return 'danger';
}

function priorityColor(string $priority): string {
    return match($priority) {
        'high'   => 'danger',
        'medium' => 'warning',
        'low'    => 'info',
        default  => 'secondary',
    };
}