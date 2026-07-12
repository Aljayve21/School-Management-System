function toggleSidebar() {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    if (overlay) overlay.classList.toggle('active');
}

document.addEventListener('click', function(e) {
    var sidebar = document.getElementById('sidebar');
    var overlay = document.getElementById('sidebarOverlay');
    if (sidebar && sidebar.classList.contains('show') && overlay && e.target === overlay) {
        sidebar.classList.remove('show');
        overlay.classList.remove('active');
    }
});

var APP_URL = window.location.origin + '/simpleproject';

function ajaxPost(formId, url, successMsg) {
    $(formId).on('submit', function(e) {
        e.preventDefault();
        var btn = $(this).find('button[type="submit"]');
        var origText = btn.html();
        btn.prop('disabled', true).html('<i class="bi bi-spinner bi-spin me-1"></i> Saving...');

        $.ajax({
            url: url,
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(res) {
                btn.prop('disabled', false).html(origText);
                if (res.status === 'success') {
                    Swal.fire({ icon: 'success', title: successMsg || 'Success', timer: 1500, showConfirmButton: false })
                        .then(function() { location.reload(); });
                } else {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message });
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false).html(origText);
                var res = xhr.responseJSON;
                Swal.fire({ icon: 'error', title: 'Error', text: res ? res.message : 'Request failed' });
            }
        });
    });
}

function deletePost(url, name) {
    Swal.fire({
        title: 'Delete "' + name + '"?',
        text: "This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        confirmButtonText: 'Yes, delete it!'
    }).then(function(result) {
        if (result.isConfirmed) {
            $.ajax({
                url: url,
                type: 'POST',
                data: { id: arguments[0] ? arguments[0] : '' },
                dataType: 'json',
                success: function(res) {
                    Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false })
                        .then(function() { location.reload(); });
                },
                error: function(xhr) {
                    var res = xhr.responseJSON;
                    Swal.fire({ icon: 'error', title: 'Error', text: res ? res.message : 'Failed' });
                }
            });
        }
    });
}

$(document).ready(function() {

    ajaxPost('#addStudentForm', APP_URL + '/admin/students/store', 'Student added!');
    ajaxPost('#editStudentForm', APP_URL + '/admin/students/update', 'Student updated!');

    $(document).on('click', '.edit-btn', function() {
        $('#edit_student_id').val($(this).data('id'));
        $('#edit_firstname').val($(this).data('firstname'));
        $('#edit_lastname').val($(this).data('lastname'));
        $('#edit_grade').val($(this).data('grade'));
        $('#edit_section').val($(this).data('section'));
        $('#edit_guardian').val($(this).data('guardian'));
        $('#edit_gphone').val($(this).data('gphone'));
        $('#edit_address').val($(this).data('address'));
    });

    $(document).on('click', '.delete-btn', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var url = window.location.pathname.replace(/\/[^\/]+$/, '/delete');
        if (url.indexOf('students') > -1) url = APP_URL + '/admin/students/delete';
        else if (url.indexOf('teachers') > -1) url = APP_URL + '/admin/teachers/delete';
        else if (url.indexOf('subjects') > -1) url = APP_URL + '/admin/subjects/delete';
        else if (url.indexOf('schedules') > -1) url = APP_URL + '/admin/schedules/delete';
        else if (url.indexOf('announcements') > -1) url = APP_URL + '/admin/announcements/delete';

        Swal.fire({
            title: 'Delete "' + name + '"?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            confirmButtonText: 'Yes, delete it!'
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(res) {
                        Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false })
                            .then(function() { location.reload(); });
                    },
                    error: function(xhr) {
                        var res = xhr.responseJSON;
                        Swal.fire({ icon: 'error', title: 'Error', text: res ? res.message : 'Failed' });
                    }
                });
            }
        });
    });

    ajaxPost('#addTeacherForm', APP_URL + '/admin/teachers/store', 'Teacher added!');
    ajaxPost('#editTeacherForm', APP_URL + '/admin/teachers/update', 'Teacher updated!');

    $(document).on('click', '.edit-teacher', function() {
        $('#edit_teacher_id').val($(this).data('id'));
        $('#edit_t_firstname').val($(this).data('firstname'));
        $('#edit_t_lastname').val($(this).data('lastname'));
        $('#edit_t_dept').val($(this).data('dept'));
        $('#edit_t_spec').val($(this).data('spec'));
        $('#edit_t_phone').val($(this).data('phone'));
        $('#edit_t_address').val($(this).data('address'));
    });

    ajaxPost('#addSubjectForm', APP_URL + '/admin/subjects/store', 'Subject added!');
    ajaxPost('#editSubjectForm', APP_URL + '/admin/subjects/update', 'Subject updated!');

    $(document).on('click', '.edit-subject', function() {
        $('#edit_subj_id').val($(this).data('id'));
        $('#edit_subj_name').val($(this).data('name'));
        $('#edit_subj_desc').val($(this).data('desc'));
        $('#edit_subj_grade').val($(this).data('grade'));
        $('#edit_subj_credits').val($(this).data('credits'));
        $('#edit_subj_status').val($(this).data('status'));
    });

    ajaxPost('#addScheduleForm', APP_URL + '/admin/schedules/store', 'Schedule added!');
    ajaxPost('#editScheduleForm', APP_URL + '/admin/schedules/update', 'Schedule updated!');

    $(document).on('click', '.edit-schedule', function() {
        $('#edit_sc_id').val($(this).data('id'));
        $('#edit_sc_subject').val($(this).data('subject'));
        $('#edit_sc_teacher').val($(this).data('teacher'));
        $('#edit_sc_grade').val($(this).data('grade'));
        $('#edit_sc_section').val($(this).data('section'));
        $('#edit_sc_day').val($(this).data('day'));
        $('#edit_sc_start').val($(this).data('start'));
        $('#edit_sc_end').val($(this).data('end'));
        $('#edit_sc_room').val($(this).data('room'));
    });

    ajaxPost('#addGradeForm', APP_URL + '/admin/grades/store', 'Grade saved!');
    ajaxPost('#editGradeForm', APP_URL + '/admin/grades/update', 'Grade updated!');

    $(document).on('click', '.edit-grade', function() {
        $('#edit_grade_id').val($(this).data('id'));
        $('#edit_g_ww').val($(this).data('ww'));
        $('#edit_g_pt').val($(this).data('pt'));
        $('#edit_g_exam').val($(this).data('exam'));
    });

    $('#filterGradesBtn').on('click', function() {
        var subjectId = $('#filterSubject').val();
        var sem = $('#filterSemester').val();
        var year = $('#filterYear').val();
        if (!subjectId) { Swal.fire('Please select a subject'); return; }

        $.ajax({
            url: APP_URL + '/admin/reports/generate',
            type: 'POST',
            data: { report_type: 'class', subject_id: subjectId, semester: sem, academic_year: year },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success' && res.data.grades && res.data.grades.length > 0) {
                    var html = '';
                    res.data.grades.forEach(function(g) {
                        html += '<tr>';
                        html += '<td>' + g.student_last + ', ' + g.student_first + '</td>';
                        html += '<td>' + (g.subject_name || '') + '</td>';
                        html += '<td>' + sem + '</td>';
                        html += '<td>' + parseFloat(g.written_work).toFixed(2) + '</td>';
                        html += '<td>' + parseFloat(g.performance_task).toFixed(2) + '</td>';
                        html += '<td>' + parseFloat(g.quarterly_exam).toFixed(2) + '</td>';
                        html += '<td><span class="badge bg-primary">' + parseFloat(g.quarterly_grade).toFixed(2) + '</span></td>';
                        html += '<td><small>' + (g.remarks || '') + '</small></td>';
                        html += '<td><button class="btn btn-sm btn-outline-primary edit-grade" data-id="' + g.id + '" data-ww="' + g.written_work + '" data-pt="' + g.performance_task + '" data-exam="' + g.quarterly_exam + '" data-bs-toggle="modal" data-bs-target="#editGradeModal"><i class="bi bi-pencil"></i></button></td>';
                        html += '</tr>';
                    });
                    $('#gradesBody').html(html);
                } else {
                    $('#gradesBody').html('<tr><td colspan="9" class="text-center text-muted py-3">No grades found for this filter.</td></tr>');
                }
            }
        });
    });

    ajaxPost('#addAnnouncementForm', APP_URL + '/admin/announcements/store', 'Announcement posted!');
    ajaxPost('#editAnnouncementForm', APP_URL + '/admin/announcements/update', 'Announcement updated!');

    $(document).on('click', '.edit-ann', function() {
        $('#edit_ann_id').val($(this).data('id'));
        $('#edit_ann_title').val($(this).data('title'));
        $('#edit_ann_content').val($(this).data('content'));
        $('#edit_ann_target').val($(this).data('target'));
        $('#edit_ann_priority').val($(this).data('priority'));
    });

    $(document).on('click', '.delete-ann', function() {
        var id = $(this).data('id');
        var title = $(this).data('title');
        Swal.fire({ title: 'Delete "' + title + '"?', icon: 'warning', showCancelButton: true, confirmButtonColor: '#dc3545', confirmButtonText: 'Delete' })
            .then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({ url: APP_URL + '/admin/announcements/delete', type: 'POST', data: { id: id }, dataType: 'json',
                        success: function() { Swal.fire({ icon: 'success', title: 'Deleted!', timer: 1500, showConfirmButton: false }).then(function() { location.reload(); }); }
                    });
                }
            });
    });

    $(document).on('click', '.toggle-ann', function() {
        var id = $(this).data('id');
        $.ajax({ url: APP_URL + '/admin/announcements/update', type: 'POST', data: { id: id, title: 'toggle', content: 'toggle' }, dataType: 'json',
            success: function() { location.reload(); }
        });
    });

    $('#studentReportForm, #classReportForm, #teacherReportForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var type = form.find('[name="report_type"]').val() || form.attr('id').replace('ReportForm', '').replace('student', 'student').replace('class', 'class').replace('teacher', 'teacher');
        if (form.attr('id') === 'studentReportForm') type = 'student';
        else if (form.attr('id') === 'classReportForm') type = 'class';
        else if (form.attr('id') === 'teacherReportForm') type = 'teacher';

        $.ajax({
            url: APP_URL + '/admin/reports/generate',
            type: 'POST',
            data: form.serialize() + '&report_type=' + type,
            dataType: 'json',
            success: function(res) {
                if (res.status !== 'success' || !res.data) {
                    Swal.fire({ icon: 'error', title: 'Error', text: res.message || 'No data returned' });
                    return;
                }
                var d = res.data;
                var html = '';
                if (type === 'student' && d.student) {
                    html += '<div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-person me-2"></i>Student Report Card - ' + d.year + '</h6></div><div class="card-body">';
                    html += '<p class="mb-3"><strong>' + d.student.first_name + ' ' + d.student.last_name + '</strong> (ID: ' + d.student.student_id + ') — Grade ' + d.student.grade_level + ' ' + (d.student.section || '') + '</p>';
                    for (var sem in d.semesters) {
                        var s = d.semesters[sem];
                        var avg = s.average || 0;
                        html += '<h6 class="mt-3">' + (sem == 1 ? '1st' : '2nd') + ' Semester — Average: <span class="badge bg-primary">' + parseFloat(avg).toFixed(2) + '</span> ' + (s.remarks || '') + '</h6>';
                        if (s.grades && s.grades.length > 0) {
                            html += '<div class="table-responsive"><table class="table table-sm table-bordered"><thead class="table-light"><tr><th>Subject</th><th>Written Work (30%)</th><th>Performance (50%)</th><th>Exam (20%)</th><th>Quarterly Grade</th><th>Remarks</th></tr></thead><tbody>';
                            s.grades.forEach(function(g) {
                                html += '<tr><td>' + (g.subject_name || '') + '</td><td>' + parseFloat(g.written_work).toFixed(2) + '</td><td>' + parseFloat(g.performance_task).toFixed(2) + '</td><td>' + parseFloat(g.quarterly_exam).toFixed(2) + '</td><td><span class="badge bg-primary">' + parseFloat(g.quarterly_grade).toFixed(2) + '</span></td><td><small>' + (g.remarks || '') + '</small></td></tr>';
                            });
                            html += '</tbody></table></div>';
                        } else {
                            html += '<p class="text-muted small">No grades recorded.</p>';
                        }
                    }
                    html += '</div></div>';
                } else if (type === 'class' && d.subject) {
                    html += '<div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-journal-text me-2"></i>Class Report — ' + (d.subject.subject_name || '') + ' (' + (d.semester == 1 ? '1st' : '2nd') + ' Sem, ' + d.academic_year + ')</h6></div><div class="card-body">';
                    html += '<div class="row g-3 mb-3"><div class="col-md-3"><div class="text-center p-2 rounded bg-light"><h5 class="mb-0 text-primary">' + d.total_students + '</h5><small class="text-muted">Students</small></div></div><div class="col-md-3"><div class="text-center p-2 rounded bg-light"><h5 class="mb-0 text-success">' + parseFloat(d.class_average).toFixed(2) + '</h5><small class="text-muted">Class Average</small></div></div><div class="col-md-3"><div class="text-center p-2 rounded bg-light"><h5 class="mb-0 text-info">' + parseFloat(d.highest_grade).toFixed(2) + '</h5><small class="text-muted">Highest</small></div></div><div class="col-md-3"><div class="text-center p-2 rounded bg-light"><h5 class="mb-0 text-danger">' + parseFloat(d.lowest_grade).toFixed(2) + '</h5><small class="text-muted">Lowest</small></div></div></div>';
                    if (d.grades && d.grades.length > 0) {
                        html += '<div class="table-responsive"><table class="table table-sm table-hover"><thead class="table-light"><tr><th>#</th><th>Student</th><th>Written Work</th><th>Performance</th><th>Exam</th><th>Grade</th><th>Remarks</th></tr></thead><tbody>';
                        d.grades.forEach(function(g, i) {
                            html += '<tr><td>' + (i + 1) + '</td><td>' + (g.student_last || '') + ', ' + (g.student_first || '') + '</td><td>' + parseFloat(g.written_work).toFixed(2) + '</td><td>' + parseFloat(g.performance_task).toFixed(2) + '</td><td>' + parseFloat(g.quarterly_exam).toFixed(2) + '</td><td><span class="badge bg-primary">' + parseFloat(g.quarterly_grade).toFixed(2) + '</span></td><td><small>' + (g.remarks || '') + '</small></td></tr>';
                        });
                        html += '</tbody></table></div>';
                    } else {
                        html += '<p class="text-muted text-center py-3">No grades found for this report.</p>';
                    }
                    html += '</div></div>';
                } else if (type === 'teacher' && d.teacher) {
                    html += '<div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0 fw-semibold"><i class="bi bi-person-workspace me-2"></i>Teacher Report — ' + (d.teacher.first_name || '') + ' ' + (d.teacher.last_name || '') + '</h6></div><div class="card-body">';
                    if (d.schedules && d.schedules.length > 0) {
                        html += '<h6>Assigned Schedules</h6><div class="table-responsive"><table class="table table-sm table-hover"><thead class="table-light"><tr><th>Subject</th><th>Grade</th><th>Section</th><th>Day</th><th>Time</th><th>Room</th></tr></thead><tbody>';
                        d.schedules.forEach(function(sc) {
                            html += '<tr><td>' + (sc.subject_name || '') + '</td><td>Grade ' + sc.grade_level + '</td><td>' + (sc.section || '') + '</td><td><span class="badge bg-info">' + (sc.day_of_week || '') + '</span></td><td>' + (sc.time_start || '') + ' - ' + (sc.time_end || '') + '</td><td>' + (sc.room || '-') + '</td></tr>';
                        });
                        html += '</tbody></table></div>';
                    }
                    if (d.subject_stats && d.subject_stats.length > 0) {
                        html += '<h6 class="mt-3">Subject Performance</h6><div class="table-responsive"><table class="table table-sm table-hover"><thead class="table-light"><tr><th>Subject</th><th>Semester</th><th>Students</th><th>Average</th></tr></thead><tbody>';
                        d.subject_stats.forEach(function(st) {
                            html += '<tr><td>' + (st.subject ? st.subject.subject_name : '') + '</td><td>' + (st.semester == 1 ? '1st' : '2nd') + '</td><td>' + st.student_count + '</td><td><span class="badge bg-primary">' + parseFloat(st.average).toFixed(2) + '</span></td></tr>';
                        });
                        html += '</tbody></table></div>';
                    }
                    html += '</div></div>';
                }
                if (!html) {
                    html = '<div class="alert alert-warning">No report data available.</div>';
                }
                $('#reportResult').html(html);
            }
        });
    });

    $('#filterLogUser').on('change', function() {
        var userId = $(this).val();
        window.location.href = APP_URL + '/admin/activity-logs' + (userId ? '?user_id=' + userId : '');
    });

    $(document).on('click', '.edit-btn, .edit-teacher, .edit-subject, .edit-schedule, .edit-grade, .edit-ann', function() {
        var modal = $(this).data('bs-target');
        if (modal) {
            var modalEl = document.querySelector(modal);
            if (modalEl) {
                var bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            }
        }
    });
});