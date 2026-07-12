<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Grade Management</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal"><i class="bi bi-plus-lg me-1"></i> Add Grade</button>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0">Filter Grades</h6></div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Subject</label>
                <select class="form-select" id="filterSubject"><option value="">All Subjects</option><?php foreach(($subjects ?? []) as $s):?><option value="<?=$s['id']?>"><?=sanitize($s['subject_name'])?></option><?php endforeach;?></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Semester</label>
                <select class="form-select" id="filterSemester"><option value="1">1st Semester</option><option value="2">2nd Semester</option></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Academic Year</label>
                <input type="text" class="form-control" id="filterYear" value="<?= date('Y') ?>">
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button class="btn btn-outline-primary w-100" id="filterGradesBtn"><i class="bi bi-search me-1"></i> Filter</button>
            </div>
        </div>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="gradesTable">
                <thead class="table-light">
                    <tr><th>Student</th><th>Subject</th><th>Sem</th><th>Written Work</th><th>Performance</th><th>Exam</th><th>Grade</th><th>Remarks</th><th>Actions</th></tr>
                </thead>
                <tbody id="gradesBody">
                    <tr><td colspan="9" class="text-center text-muted py-3">Select filters and click Filter to view grades.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addGradeModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Grade</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="addGradeForm"><div class="modal-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Student *</label><select class="form-select" name="student_id" required><option value="">Select</option><?php foreach(($students ?? []) as $st):?><option value="<?=$st['id']?>"><?=sanitize($st['student_id'].' - '.$st['first_name'].' '.$st['last_name'])?></option><?php endforeach;?></select></div>
                <div class="col-12"><label class="form-label">Subject *</label><select class="form-select" name="subject_id" required><option value="">Select</option><?php foreach(($subjects ?? []) as $s):?><option value="<?=$s['id']?>"><?=sanitize($s['subject_name'])?></option><?php endforeach;?></select></div>
                <div class="col-6"><label class="form-label">Semester *</label><select class="form-select" name="semester" required><option value="1">1st</option><option value="2">2nd</option></select></div>
                <div class="col-6"><label class="form-label">Academic Year *</label><input type="text" class="form-control" name="academic_year" value="<?= date('Y') ?>" required></div>
                <div class="col-4"><label class="form-label">Written Work *</label><input type="number" class="form-control" name="written_work" step="0.01" min="0" max="100" required></div>
                <div class="col-4"><label class="form-label">Performance *</label><input type="number" class="form-control" name="performance_task" step="0.01" min="0" max="100" required></div>
                <div class="col-4"><label class="form-label">Exam *</label><input type="number" class="form-control" name="quarterly_exam" step="0.01" min="0" max="100" required></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form>
    </div></div>
</div>

<div class="modal fade" id="editGradeModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Grade</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="editGradeForm"><div class="modal-body">
            <input type="hidden" name="id" id="edit_grade_id">
            <div class="row g-3">
                <div class="col-4"><label class="form-label">Written Work</label><input type="number" class="form-control" name="written_work" id="edit_g_ww" step="0.01" min="0" max="100" required></div>
                <div class="col-4"><label class="form-label">Performance</label><input type="number" class="form-control" name="performance_task" id="edit_g_pt" step="0.01" min="0" max="100" required></div>
                <div class="col-4"><label class="form-label">Exam</label><input type="number" class="form-control" name="quarterly_exam" id="edit_g_exam" step="0.01" min="0" max="100" required></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form>
    </div></div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php';