<?php ob_start(); ?>
<div class="row g-3 g-md-4 mb-4">
    <div class="col-4">
        <div class="card border-0 shadow-sm bg-info text-white stat-card">
            <div class="card-body"><h6 class="opacity-75">Grade Level</h6><h2 class="mb-0"><?= $student['grade_level'] ?? '-' ?></h2></div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm bg-primary text-white stat-card">
            <div class="card-body"><h6 class="opacity-75">Section</h6><h2 class="mb-0"><?= sanitize($student['section'] ?? '-') ?></h2></div>
        </div>
    </div>
    <div class="col-4">
        <div class="card border-0 shadow-sm bg-success text-white stat-card">
            <div class="card-body"><h6 class="opacity-75">Announcements</h6><h2 class="mb-0"><?= count($announcements ?? []) ?></h2></div>
        </div>
    </div>
</div>
<div class="row g-3 g-md-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">My Weekly Schedule</h6></div>
            <div class="card-body">
                <?php if (!empty($schedules)): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Day</th><th>Subject</th><th>Teacher</th><th>Time</th><th>Room</th></tr></thead>
                        <tbody>
                            <?php foreach (($schedules ?? []) as $sc): ?>
                            <tr>
                                <td><span class="badge bg-info"><?= $sc['day_of_week'] ?></span></td>
                                <td><?= sanitize($sc['subject_name']) ?></td>
                                <td><?= sanitize($sc['teacher_first'] . ' ' . $sc['teacher_last']) ?></td>
                                <td><?= formatTime($sc['time_start']) ?> - <?= formatTime($sc['time_end']) ?></td>
                                <td><?= sanitize($sc['room'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted text-center">No schedule available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0">Recent Announcements</h6></div>
            <div class="card-body">
                <?php if (!empty($announcements)): ?>
                    <?php foreach (array_slice(($announcements ?? []), 0, 5) as $a): ?>
                    <div class="border-bottom pb-2 mb-2">
                        <span class="badge bg-<?= priorityColor($a['priority']) ?> me-1"><?= ucfirst($a['priority']) ?></span>
                        <strong><?= sanitize($a['title']) ?></strong>
                        <p class="mb-0 small text-muted"><?= timeAgo($a['created_at']) ?></p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <p class="text-muted text-center">No announcements.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/student.php';