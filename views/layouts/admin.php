<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= sanitize($title ?? 'School MS') ?> - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="<?= asset('css/style.css') ?>" rel="stylesheet">
</head>
<body>
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
<div class="d-flex">
    <nav id="sidebar" class="sidebar bg-dark text-white">
        <div class="sidebar-header p-3 text-center border-bottom border-secondary">
            <i class="bi bi-mortarboard-fill fs-1 text-primary"></i>
            <h5 class="mt-2 mb-0">School MS</h5>
            <small class="text-muted">Admin Panel</small>
        </div>
        <ul class="nav flex-column p-2">
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'dashboard' ? 'active bg-primary' : '' ?>" href="<?= url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'students' ? 'active bg-primary' : '' ?>" href="<?= url('admin/students') ?>">
                    <i class="bi bi-people me-2"></i> Students
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'teachers' ? 'active bg-primary' : '' ?>" href="<?= url('admin/teachers') ?>">
                    <i class="bi bi-person-workspace me-2"></i> Teachers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'subjects' ? 'active bg-primary' : '' ?>" href="<?= url('admin/subjects') ?>">
                    <i class="bi bi-book me-2"></i> Subjects
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'schedules' ? 'active bg-primary' : '' ?>" href="<?= url('admin/schedules') ?>">
                    <i class="bi bi-calendar-week me-2"></i> Schedules
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'grades' ? 'active bg-primary' : '' ?>" href="<?= url('admin/grades') ?>">
                    <i class="bi bi-journal-check me-2"></i> Grades
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'announcements' ? 'active bg-primary' : '' ?>" href="<?= url('admin/announcements') ?>">
                    <i class="bi bi-megaphone me-2"></i> Announcements
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'reports' ? 'active bg-primary' : '' ?>" href="<?= url('admin/reports') ?>">
                    <i class="bi bi-bar-chart-line me-2"></i> Reports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?= ($currentPage ?? '') === 'activity-logs' ? 'active bg-primary' : '' ?>" href="<?= url('admin/activity-logs') ?>">
                    <i class="bi bi-clock-history me-2"></i> Activity Logs
                </a>
            </li>
            <li class="nav-item mt-3 border-top border-secondary pt-3">
                <a class="nav-link text-white" href="<?= url('logout') ?>">
                    <i class="bi bi-box-arrow-left me-2"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
    <main class="main-content flex-grow-1">
        <div class="topbar bg-white shadow-sm p-3 d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-secondary sidebar-toggle me-2" onclick="toggleSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <h5 class="mb-0 topbar-title flex-grow-1"><?= sanitize($title ?? 'Dashboard') ?></h5>
            <div class="topbar-user d-flex align-items-center ms-2">
                <span class="me-2 d-none d-sm-inline"><i class="bi bi-person-circle"></i> <?= sanitize($session->userName() ?? 'Admin') ?></span>
                <span class="badge bg-primary"><?= ucfirst($session->userRole() ?? '') ?></span>
            </div>
        </div>
        <div class="content-wrapper p-3 p-md-4">
            <?= $content ?? '' ?>
        </div>
    </main>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="<?= asset('js/app.js') ?>"></script>
</body>
</html>