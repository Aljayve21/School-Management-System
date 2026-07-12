<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Teacher List</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
        <i class="bi bi-plus-lg me-1"></i> Add Teacher
    </button>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="teachersTable">
                <thead class="table-light">
                    <tr>
                        <th>Teacher ID</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Specialization</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($teachers ?? []) as $t): ?>
                    <tr>
                        <td><span class="badge bg-success"><?= sanitize($t['teacher_id']) ?></span></td>
                        <td><?= sanitize($t['first_name'] . ' ' . $t['last_name']) ?></td>
                        <td><?= sanitize($t['department'] ?? '-') ?></td>
                        <td><?= sanitize($t['specialization'] ?? '-') ?></td>
                        <td><?= sanitize($t['email']) ?></td>
                        <td><span class="badge bg-<?= $t['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($t['status']) ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-teacher" data-id="<?= $t['id'] ?>" data-firstname="<?= sanitize($t['first_name']) ?>" data-lastname="<?= sanitize($t['last_name']) ?>" data-dept="<?= sanitize($t['department'] ?? '') ?>" data-spec="<?= sanitize($t['specialization'] ?? '') ?>" data-phone="<?= sanitize($t['phone'] ?? '') ?>" data-address="<?= sanitize($t['address'] ?? '') ?>" data-bs-toggle="modal" data-bs-target="#editTeacherModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $t['id'] ?>" data-name="<?= sanitize($t['first_name'] . ' ' . $t['last_name']) ?>">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addTeacherForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Teacher ID *</label>
                            <input type="text" class="form-control" name="teacher_id" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="department">
                        </div>
                        <div class="col-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Teacher Modal -->
<div class="modal fade" id="editTeacherModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editTeacherForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_teacher_id">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" name="first_name" id="edit_t_firstname" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" id="edit_t_lastname" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" name="department" id="edit_t_dept">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Specialization</label>
                            <input type="text" class="form-control" name="specialization" id="edit_t_spec">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" id="edit_t_phone">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="edit_t_address" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';