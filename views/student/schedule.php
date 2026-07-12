<?php ob_start(); ?>
<h5 class="mb-4">My Schedule</h5>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if (!empty($schedules)): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Day</th><th>Subject</th><th>Teacher</th><th>Time</th><th>Room</th></tr></thead>
                <tbody>
                    <?php foreach (($schedules ?? []) as $sc): ?>
                    <tr>
                        <td><span class="badge bg-info"><?= $sc['day_of_week'] ?></span></td>
                        <td><strong><?= sanitize($sc['subject_name']) ?></strong><br><small class="text-muted"><?= sanitize($sc['subject_code']) ?></small></td>
                        <td><?= sanitize($sc['teacher_first'] . ' ' . $sc['teacher_last']) ?></td>
                        <td><?= formatTime($sc['time_start']) ?> - <?= formatTime($sc['time_end']) ?></td>
                        <td><?= sanitize($sc['room'] ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
        <p class="text-muted text-center py-4">No schedule available for your class.</p>
        <?php endif; ?>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/student.php';