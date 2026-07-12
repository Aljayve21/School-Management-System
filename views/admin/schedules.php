<?php ob_start(); ?>
<div class="page-header">
    <h5 class="mb-0">Class Schedules</h5>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScheduleModal"><i class="bi bi-plus-lg me-1"></i> Add Schedule</button>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="schedulesTable">
                <thead class="table-light">
                    <tr><th>Subject</th><th>Teacher</th><th>Grade</th><th>Section</th><th>Day</th><th>Time</th><th>Room</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach (($schedules ?? []) as $sc): ?>
                    <tr>
                        <td><?= sanitize($sc['subject_name']) ?></td>
                        <td><?= sanitize($sc['teacher_first'] . ' ' . $sc['teacher_last']) ?></td>
                        <td>Grade <?= $sc['grade_level'] ?></td>
                        <td><?= sanitize($sc['section']) ?></td>
                        <td><span class="badge bg-info"><?= $sc['day_of_week'] ?></span></td>
                        <td><?= formatTime($sc['time_start']) ?> - <?= formatTime($sc['time_end']) ?></td>
                        <td><?= sanitize($sc['room'] ?? '-') ?></td>
                        <td>
                            <button class="btn btn-sm btn-outline-primary edit-schedule" data-id="<?= $sc['id'] ?>" data-subject="<?= $sc['subject_id'] ?>" data-teacher="<?= $sc['teacher_id'] ?>" data-grade="<?= $sc['grade_level'] ?>" data-section="<?= sanitize($sc['section']) ?>" data-day="<?= $sc['day_of_week'] ?>" data-start="<?= $sc['time_start'] ?>" data-end="<?= $sc['time_end'] ?>" data-room="<?= sanitize($sc['room'] ?? '') ?>" data-bs-toggle="modal" data-bs-target="#editScheduleModal"><i class="bi bi-pencil"></i></button>
                            <button class="btn btn-sm btn-outline-danger delete-btn" data-id="<?= $sc['id'] ?>" data-name="<?= sanitize($sc['subject_name'] . ' - ' . $sc['day_of_week']) ?>"><i class="bi bi-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addScheduleModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Add Schedule</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="addScheduleForm"><div class="modal-body">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Subject *</label><select class="form-select" name="subject_id" required><option value="">Select</option><?php foreach(($subjects ?? []) as $s):?><option value="<?=$s['id']?>"><?=sanitize($s['subject_name'])?></option><?php endforeach;?></select></div>
                <div class="col-12"><label class="form-label">Teacher *</label><select class="form-select" name="teacher_id" required><option value="">Select</option><?php foreach(($teachers ?? []) as $t):?><option value="<?=$t['id']?>"><?=sanitize($t['first_name'].' '.$t['last_name'])?></option><?php endforeach;?></select></div>
                <div class="col-4"><label class="form-label">Grade *</label><select class="form-select" name="grade_level" required><option value="">Select</option><?php for($i=1;$i<=12;$i++):?><option value="<?=$i?>">Grade <?=$i?></option><?php endfor;?></select></div>
                <div class="col-4"><label class="form-label">Section *</label><input type="text" class="form-control" name="section" required></div>
                <div class="col-4"><label class="form-label">Room</label><input type="text" class="form-control" name="room"></div>
                <div class="col-12"><label class="form-label">Day *</label><select class="form-select" name="day_of_week" required><option value="">Select</option><option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option></select></div>
                <div class="col-6"><label class="form-label">Start Time *</label><input type="time" class="form-control" name="time_start" required></div>
                <div class="col-6"><label class="form-label">End Time *</label><input type="time" class="form-control" name="time_end" required></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Save</button></div></form>
    </div></div>
</div>

<div class="modal fade" id="editScheduleModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content">
        <div class="modal-header"><h5 class="modal-title">Edit Schedule</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <form id="editScheduleForm"><div class="modal-body">
            <input type="hidden" name="id" id="edit_sc_id">
            <div class="row g-3">
                <div class="col-12"><label class="form-label">Subject *</label><select class="form-select" name="subject_id" id="edit_sc_subject" required><option value="">Select</option><?php foreach(($subjects ?? []) as $s):?><option value="<?=$s['id']?>"><?=sanitize($s['subject_name'])?></option><?php endforeach;?></select></div>
                <div class="col-12"><label class="form-label">Teacher *</label><select class="form-select" name="teacher_id" id="edit_sc_teacher" required><option value="">Select</option><?php foreach(($teachers ?? []) as $t):?><option value="<?=$t['id']?>"><?=sanitize($t['first_name'].' '.$t['last_name'])?></option><?php endforeach;?></select></div>
                <div class="col-4"><label class="form-label">Grade *</label><select class="form-select" name="grade_level" id="edit_sc_grade" required><?php for($i=1;$i<=12;$i++):?><option value="<?=$i?>">Grade <?=$i?></option><?php endfor;?></select></div>
                <div class="col-4"><label class="form-label">Section *</label><input type="text" class="form-control" name="section" id="edit_sc_section" required></div>
                <div class="col-4"><label class="form-label">Room</label><input type="text" class="form-control" name="room" id="edit_sc_room"></div>
                <div class="col-12"><label class="form-label">Day *</label><select class="form-select" name="day_of_week" id="edit_sc_day" required><option>Monday</option><option>Tuesday</option><option>Wednesday</option><option>Thursday</option><option>Friday</option></select></div>
                <div class="col-6"><label class="form-label">Start Time *</label><input type="time" class="form-control" name="time_start" id="edit_sc_start" required></div>
                <div class="col-6"><label class="form-label">End Time *</label><input type="time" class="form-control" name="time_end" id="edit_sc_end" required></div>
            </div>
        </div>
        <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn btn-primary">Update</button></div></form>
    </div></div>
</div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php';