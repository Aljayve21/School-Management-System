<?php ob_start(); ?>
<h5 class="mb-4">Input Grades</h5>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Subject</label>
                <select class="form-select" id="t_gradeSubject">
                    <option value="">Select</option>
                    <?php foreach (($subjects ?? []) as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= sanitize($s['subject_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Semester</label>
                <select class="form-select" id="t_gradeSem"><option value="1">1st</option><option value="2">2nd</option></select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Year</label>
                <input type="text" class="form-control" id="t_gradeYear" value="<?= date('Y') ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-outline-primary w-100" id="loadStudentsBtn"><i class="bi bi-search me-1"></i> Load Students</button>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Student</th><th>Written Work</th><th>Performance</th><th>Exam</th><th>Actions</th></tr></thead>
                <tbody id="t_gradesBody">
                    <tr><td colspan="5" class="text-center text-muted py-3">Select a subject and click Load Students.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/teacher.php';