<?php ob_start(); ?>
<h5 class="mb-4">My Grades - <?= sanitize($academic_year ?? '') ?> (<?= $semester == 1 ? '1st' : '2nd' ?> Semester)</h5>
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-<?= gradeColor($general_average ?? 0) ?> text-white text-center p-3">
            <h6 class="opacity-75">General Average</h6>
            <h1 class="mb-0"><?= number_format($general_average ?? 0, 2) ?></h1>
            <small><?= (new GradeService())->getRemarks($general_average ?? 0) ?></small>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light"><tr><th>Subject</th><th>Written Work (30%)</th><th>Performance (50%)</th><th>Exam (20%)</th><th>Quarterly Grade</th><th>Remarks</th></tr></thead>
                <tbody>
                    <?php foreach ($grades ?? [] as $g): ?>
                    <tr>
                        <td><strong><?= sanitize($g['subject_name']) ?></strong></td>
                        <td><?= number_format($g['written_work'], 2) ?></td>
                        <td><?= number_format($g['performance_task'], 2) ?></td>
                        <td><?= number_format($g['quarterly_exam'], 2) ?></td>
                        <td><span class="badge bg-<?= gradeColor($g['quarterly_grade']) ?> fs-6"><?= number_format($g['quarterly_grade'], 2) ?></span></td>
                        <td><small><?= sanitize($g['remarks']) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($grades)): ?>
                    <tr><td colspan="6" class="text-center text-muted py-3">No grades available yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/student.php';