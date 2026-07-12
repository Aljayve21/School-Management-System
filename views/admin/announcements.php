<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Announcements</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAnnouncementModal"><i class="bi bi-plus-lg me-1"></i> New Announcement</button>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Title</th><th>Target</th><th>Priority</th><th>Status</th><th>Posted</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach (($announcements ?? []) as $a): ?>
                    <tr>
                        <td><strong><?= sanitize($a['title']) ?></strong><br><small class="text-muted"><?= sanitize(substr($a['content'], 0, 80)) ?>...</small></td>
                        <td><span class="badge bg-secondary"><?= ucfirst($a['target_role']) ?></span></td>
                        <td><span class="badge bg-<?= priorityColor($a['priority']) ?>"><?= ucfirst($a['priority']) ?></span></td>
                        <td><span class="badge bg-<?= $a['is_active'] ? 'success' : 'danger' ?>"><?= $a['is_active'] ? 'Active' : 'Inactive' ?></span></td>
                        <td><small><?= timeAgo($a['created_at']) ?></small></td>
                        <td>
                            <button class="btn btn-sm btn-outline-warning toggle-ann" data-id="<?= $a['id'] ?>" title="Toggle Status"><i class="bi bi-toggle-on"></i></button>
                            <button class="btn btn-sm btn-outline-primary edit-ann" data-id="<?= $a['id'] ?>" data-title="<?= sanitize($a['title']) ?>" data-content="<?= sanitize($a['content']) ?>" data-target="<?= $a['target_role'] ?>" data-priority="<?= $a['priority'] ?>" data-bs-toggle="modal" data-bs-target="#editAnnouncementModal"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger delete-ann" data-id="<?= $a['id'] ?>" data-title="<?= sanitize($a['title']) ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addAnnouncementModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">New Announcement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="addAnnouncementForm"><div class="modal-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Title *</label><input type="text" class="form-control" name="title" required></div>
                <div class="col-12"><label class="form-label">Content *</label><textarea class="form-control" name="content" rows="4" required></textarea></div>
                <div class="col-6"><label class="form-label">Target Audience *</label><select class="form-select" name="target_role" required><option value="all">All</option><option value="admin">Admin Only</option><option value="teacher">Teachers Only</option><option value="student">Students Only</option></select></div>
                <div class="col-6"><label class="form-label">Priority</label><select class="form-select" name="priority"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option></select></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Post</button></div></form>
    </div></div>
</div>

<div class="modal fade" id="editAnnouncementModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Announcement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="editAnnouncementForm"><div class="modal-body">
            <input type="hidden" name="id" id="edit_ann_id">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Title *</label><input type="text" class="form-control" name="title" id="edit_ann_title" required></div>
                <div class="col-12"><label class="form-label">Content *</label><textarea class="form-control" name="content" id="edit_ann_content" rows="4" required></textarea></div>
                <div class="col-6"><label class="form-label">Target</label><select class="form-select" name="target_role" id="edit_ann_target"><option value="all">All</option><option value="admin">Admin</option><option value="teacher">Teacher</option><option value="student">Student</option></select></div>
                <div class="col-6"><label class="form-label">Priority</label><select class="form-select" name="priority" id="edit_ann_priority"><option value="low">Low</option><option value="medium">Medium</option><option value="high">High</option></select></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form>
    </div></div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php';