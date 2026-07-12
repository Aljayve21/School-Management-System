<?php ob_start(); ?>
<div class="row g-3 g-md-4 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-primary text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Students</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['total_students'] ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-people fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-success text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Teachers</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['total_teachers'] ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-person-workspace fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-warning text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Subjects</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['total_subjects'] ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-book fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card border-0 shadow-sm bg-info text-white stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Schedules</h6>
                        <h2 class="mb-0 fw-bold"><?= $stats['total_schedules'] ?? 0 ?></h2>
                    </div>
                    <i class="bi bi-calendar-week fs-1 opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-megaphone me-2"></i> Recent Announcements</h6>
            </div>
            <div class="card-body">
                <?php if (!empty($announcements)): ?>
                    <?php foreach (array_slice(($announcements ?? []), 0, 5) as $ann): ?>
                        <div class="d-flex border-bottom pb-3 mb-3">
                            <div class="me-3">
                                <span class="badge bg-<?= priorityColor($ann['priority']) ?>"><?= ucfirst($ann['priority']) ?></span>
                            </div>
                            <div>
                                <h6 class="mb-1"><?= sanitize($ann['title']) ?></h6>
                                <p class="mb-1 text-muted small"><?= sanitize(substr($ann['content'], 0, 150)) ?>...</p>
                                <small class="text-muted"><i class="bi bi-clock me-1"></i> <?= timeAgo($ann['created_at']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No announcements yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-info-circle me-2"></i> Quick Info</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted text-uppercase">Total Users</small>
                    <h4 class="mb-0"><?= $stats['total_users'] ?? 0 ?></h4>
                </div>
                <div class="mb-3">
                    <small class="text-muted text-uppercase">Active Announcements</small>
                    <h4 class="mb-0"><?= $stats['total_announcements'] ?? 0 ?></h4>
                </div>
                <hr>
                <small class="text-muted">Last updated: <?= date('M d, Y g:i A') ?></small>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';