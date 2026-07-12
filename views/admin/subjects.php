<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Subject List</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
        <i class="bi bi-plus-lg me-1"></i> Add Subject
    </button>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="subjectsTable">
                <thead class="table-light">
                    <tr><th>Code</th><th>Subject Name</th><th>Grade Level</th><th>Credits</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach (($subjects ?? []) as $s): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= sanitize($s['subject_code']) ?></span></td>
                        <td><?= sanitize($s['subject_name']) ?></td>
                        <td>Grade <?= $s['grade_level'] ?></td>
                        <td><?= $s['credits'] ?></td>
                        <td><span class="badge bg-<?= $s['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($s['status']) ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-subject" data-id="<?= $s['id'] ?>" data-name="<?= sanitize($s['subject_name']) ?>" data-desc="<?= sanitize($s['description'] ?? '') ?>" data-grade="<?= $s['grade_level'] ?>" data-credits="<?= $s['credits'] ?>" data-status="<?= $s['status'] ?>" data-bs-toggle="modal" data-bs-target="#editSubjectModal"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $s['id'] ?>" data-name="<?= sanitize($s['subject_name']) ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Subject</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="addSubjectForm"><div class="modal-body">
            <div class="row g-3">
                <div class="col-6"><label class="form-label">Subject Code *</label><input type="text" class="form-control" name="subject_code" required></div>
                <div class="col-6"><label class="form-label">Credits</label><input type="number" class="form-control" name="credits" value="1" min="1"></div>
                <div class="col-12"><label class="form-label">Subject Name *</label><input type="text" class="form-control" name="subject_name" required></div>
                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="2"></textarea></div>
                <div class="col-6"><label class="form-label">Grade Level *</label><select class="form-select" name="grade_level" required><option value="">Select</option><?php for($i=1;$i<=12;$i++):?><option value="<?=$i?>">Grade <?=$i?></option><?php endfor;?></select></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form>
    </div></div>
</div>

<div class="modal fade" id="editSubjectModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Subject</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="editSubjectForm"><div class="modal-body">
            <input type="hidden" name="id" id="edit_subj_id">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Subject Name *</label><input type="text" class="form-control" name="subject_name" id="edit_subj_name" required></div>
                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" id="edit_subj_desc" rows="2"></textarea></div>
                <div class="col-6"><label class="form-label">Grade Level *</label><select class="form-select" name="grade_level" id="edit_subj_grade" required><?php for($i=1;$i<=12;$i++):?><option value="<?=$i?>">Grade <?=$i?></option><?php endfor;?></select></div>
                <div class="col-6"><label class="form-label">Credits</label><input type="number" class="form-control" name="credits" id="edit_subj_credits" min="1"></div>
                <div class="col-6"><label class="form-label">Status</label><select class="form-select" name="status" id="edit_subj_status"><option value="active">Active</option><option value="inactive">Inactive</option></select></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form>
    </div></div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php';