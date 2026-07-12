<?php ob_start(); ?>
<h5 class="mb-4">Announcements</h5>
<?php if (!empty($announcements)): ?>
    <?php foreach (($announcements ?? []) as $a): ?>
    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <span class="badge bg-<?= priorityColor($a['priority']) ?> me-2"><?= ucfirst($a['priority']) ?></span>
                    <h5 class="d-inline"><?= sanitize($a['title']) ?></h5>
                </div>
                <small class="text-muted"><?= timeAgo($a['created_at']) ?></small>
            </div>
            <p class="mt-2 mb-0"><?= nl2br(sanitize($a['content'])) ?></p>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
<div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5"><i class="bi bi-megaphone display-4"></i><p class="mt-3">No announcements at this time.</p></div></div>
<?php endif; ?>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/teacher.php';