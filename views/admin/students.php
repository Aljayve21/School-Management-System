<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Student List</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStudentModal">
        <i class="bi bi-plus-lg me-1"></i> Add Student
    </button>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="studentsTable">
                <thead class="table-light">
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Section</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (($students ?? []) as $s): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= sanitize($s['student_id']) ?></span></td>
                        <td><?= sanitize($s['first_name'] . ' ' . $s['last_name']) ?></td>
                        <td><?= $s['grade_level'] ?></td>
                        <td><?= sanitize($s['section'] ?? '-') ?></td>
                        <td><?= sanitize($s['email']) ?></td>
                        <td><span class="badge bg-<?= $s['status'] === 'active' ? 'success' : 'danger' ?>"><?= ucfirst($s['status']) ?></span></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-btn" data-id="<?= $s['id'] ?>" data-firstname="<?= sanitize($s['first_name']) ?>" data-lastname="<?= sanitize($s['last_name']) ?>" data-grade="<?= $s['grade_level'] ?>" data-section="<?= sanitize($s['section'] ?? '') ?>" data-guardian="<?= sanitize($s['guardian_name'] ?? '') ?>" data-gphone="<?= sanitize($s['guardian_phone'] ?? '') ?>" data-address="<?= sanitize($s['address'] ?? '') ?>" data-bs-toggle="modal" data-bs-target="#editStudentModal">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $s['id'] ?>" data-name="<?= sanitize($s['first_name'] . ' ' . $s['last_name']) ?>">
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

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addStudentForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Student ID *</label>
                            <input type="text" class="form-control" name="student_id" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Grade Level *</label>
                            <select class="form-select" name="grade_level" required>
                                <option value="">Select</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>">Grade <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Section</label>
                            <input type="text" class="form-control" name="section">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Gender</label>
                            <select class="form-select" name="gender">
                                <option value="">Select</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Password *</label>
                            <input type="password" class="form-control" name="password" required minlength="6">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" class="form-control" name="guardian_name">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Guardian Phone</label>
                            <input type="text" class="form-control" name="guardian_phone">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" name="date_of_birth">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Student</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Student Modal -->
<div class="modal fade" id="editStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editStudentForm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit_student_id">
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">First Name *</label>
                            <input type="text" class="form-control" name="first_name" id="edit_firstname" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Last Name *</label>
                            <input type="text" class="form-control" name="last_name" id="edit_lastname" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Grade Level *</label>
                            <select class="form-select" name="grade_level" id="edit_grade" required>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>">Grade <?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Section</label>
                            <input type="text" class="form-control" name="section" id="edit_section">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Guardian Name</label>
                            <input type="text" class="form-control" name="guardian_name" id="edit_guardian">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Guardian Phone</label>
                            <input type="text" class="form-control" name="guardian_phone" id="edit_gphone">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" id="edit_address" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Student</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/admin.php';