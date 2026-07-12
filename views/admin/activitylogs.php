<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Activity Logs</h5>
    <div>
        <select class="form-select form-select-sm d-inline-block w-auto" id="filterLogUser">
            <option value="">All Users</option>
            <?php foreach (($users ?? []) as $u): ?>
                <option value="<?= $u['id'] ?>" <?= ($filter_user ?? '') == $u['id'] ? 'selected' : '' ?>><?= sanitize($u['email']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>Date/Time</th><th>User</th><th>Action</th><th>Entity</th><th>IP Address</th></tr>
                </thead>
                <tbody>
                    <?php foreach (($logs ?? []) as $log): ?>
                    <tr>
                        <td><small><?= formatDateTime($log['created_at']) ?></small></td>
                        <td><?= sanitize($log['user_email'] ?? 'System') ?></td>
                        <td><?= sanitize($log['action']) ?></td>
                        <td><?php if ($log['entity_type']): ?><span class="badge bg-secondary"><?= sanitize($log['entity_type']) ?></span> #<?= $log['entity_id'] ?><?php else: ?>-<?php endif; ?></td>
                        <td><small class="text-muted"><?= sanitize($log['ip_address']) ?></small></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-3">No activity logs found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php';