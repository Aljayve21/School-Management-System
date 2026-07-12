<?php ob_start(); ?>
<h5 class="mb-4">My Classes</h5>
<div class="row g-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-book me-2"></i>My Subjects</h6></div>
            <div class="card-body">
                <?php if (!empty($subjects)): ?>
                    <?php foreach (($subjects ?? []) as $s): ?>
                    <div class="d-flex align-items-center border-bottom py-2">
                        <span class="badge bg-primary me-2"><?= sanitize($s['subject_code']) ?></span>
                        <div>
                            <strong><?= sanitize($s['subject_name']) ?></strong>
                            <br><small class="text-muted">Grade <?= $s['grade_level'] ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                <p class="text-muted">No subjects assigned.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h6 class="mb-0"><i class="bi bi-calendar-week me-2"></i>Weekly Schedule</h6></div>
            <div class="card-body">
                <?php if (!empty($schedules)): ?>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead><tr><th>Day</th><th>Subject</th><th>Time</th><th>Room</th></tr></thead>
                        <tbody>
                            <?php foreach (($schedules ?? []) as $sc): ?>
                            <tr>
                                <td><span class="badge bg-info"><?= $sc['day_of_week'] ?></span></td>
                                <td><?= sanitize($sc['subject_name']) ?></td>
                                <td><?= formatTime($sc['time_start']) ?> - <?= formatTime($sc['time_end']) ?></td>
                                <td><?= sanitize($sc['room'] ?? '-') ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <p class="text-muted">No schedule assigned.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/teacher.php';