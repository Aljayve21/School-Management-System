<?php ob_start(); ?>
<div class="mb-4">
    <h5 class="mb-0">Reports</h5>
</div>
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3"><h4 class="text-primary"><?= $stats['total_students'] ?? 0 ?></h4><small class="text-muted">Total Students</small></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3"><h4 class="text-success"><?= $stats['total_teachers'] ?? 0 ?></h4><small class="text-muted">Total Teachers</small></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3"><h4 class="text-warning"><?= $stats['total_subjects'] ?? 0 ?></h4><small class="text-muted">Total Subjects</small></div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3"><h4 class="text-info"><?= $stats['total_schedules'] ?? 0 ?></h4><small class="text-muted">Total Schedules</small></div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary text-white"><h6 class="mb-0"><i class="bi bi-person me-2"></i>Student Report</h6></div>
            <div class="card-body">
                <form id="studentReportForm">
                    <div class="mb-3"><label class="form-label">Student</label><select class="form-select" name="student_id" required><option value="">Select</option><?php foreach(($students ?? []) as $s):?><option value="<?=$s['id']?>"><?=sanitize($s['student_id'].' - '.$s['first_name'].' '.$s['last_name'])?></option><?php endforeach;?></select></div>
                    <div class="mb-3"><label class="form-label">Academic Year</label><input type="text" class="form-control" name="academic_year" value="<?=date('Y')?>"></div>
                    <button type="submit" class="btn btn-primary w-100">Generate Report</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-success text-white"><h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Class Report</h6></div>
            <div class="card-body">
                <form id="classReportForm">
                    <div class="mb-3"><label class="form-label">Subject</label><select class="form-select" name="subject_id" required><option value="">Select</option><?php foreach(($subjects ?? []) as $s):?><option value="<?=$s['id']?>"><?=sanitize($s['subject_name'])?></option><?php endforeach;?></select></div>
                    <div class="mb-3"><label class="form-label">Semester</label><select class="form-select" name="semester"><option value="1">1st</option><option value="2">2nd</option></select></div>
                    <div class="mb-3"><label class="form-label">Academic Year</label><input type="text" class="form-control" name="academic_year" value="<?=date('Y')?>"></div>
                    <button type="submit" class="btn btn-success w-100">Generate Report</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-info text-white"><h6 class="mb-0"><i class="bi bi-person-workspace me-2"></i>Teacher Report</h6></div>
            <div class="card-body">
                <form id="teacherReportForm">
                    <div class="mb-3"><label class="form-label">Teacher</label><select class="form-select" name="teacher_id" required><option value="">Select</option><?php foreach(($teachers ?? []) as $t):?><option value="<?=$t['id']?>"><?=sanitize($t['first_name'].' '.$t['last_name'])?></option><?php endforeach;?></select></div>
                    <div class="mb-3"><label class="form-label">Academic Year</label><input type="text" class="form-control" name="academic_year" value="<?=date('Y')?>"></div>
                    <button type="submit" class="btn btn-info text-white w-100">Generate Report</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="reportResult" class="mt-4"></div>
<?php $content = ob_get_clean(); require __DIR__ . '/../layouts/admin.php';