<?php

function getRouter(string $role = ''): Router {
    $router = new Router();

    // Public routes
    $router->get('/',               ['AuthController', 'showLogin']);
    $router->get('/login',         ['AuthController', 'showLogin']);
    $router->post('/login',        ['AuthController', 'login']);
    $router->get('/logout',        ['AuthController', 'logout']);

    if ($role === 'admin') {
        $router->get('/admin',                    ['DashboardController', 'index']);
        $router->get('/admin/dashboard',          ['DashboardController', 'index']);
        $router->get('/admin/students',           ['StudentController', 'index']);
        $router->post('/admin/students/store',    ['StudentController', 'store']);
        $router->post('/admin/students/update',   ['StudentController', 'update']);
        $router->post('/admin/students/delete',   ['StudentController', 'destroy']);
        $router->get('/admin/teachers',           ['TeacherController', 'index']);
        $router->post('/admin/teachers/store',    ['TeacherController', 'store']);
        $router->post('/admin/teachers/update',   ['TeacherController', 'update']);
        $router->post('/admin/teachers/delete',   ['TeacherController', 'destroy']);
        $router->get('/admin/subjects',           ['SubjectController', 'index']);
        $router->post('/admin/subjects/store',    ['SubjectController', 'store']);
        $router->post('/admin/subjects/update',   ['SubjectController', 'update']);
        $router->post('/admin/subjects/delete',   ['SubjectController', 'destroy']);
        $router->get('/admin/schedules',          ['ScheduleController', 'index']);
        $router->post('/admin/schedules/store',   ['ScheduleController', 'store']);
        $router->post('/admin/schedules/update',  ['ScheduleController', 'update']);
        $router->post('/admin/schedules/delete',  ['ScheduleController', 'destroy']);
        $router->get('/admin/grades',             ['GradeController', 'index']);
        $router->post('/admin/grades/store',      ['GradeController', 'store']);
        $router->post('/admin/grades/update',     ['GradeController', 'update']);
        $router->get('/admin/announcements',      ['AnnouncementController', 'index']);
        $router->post('/admin/announcements/store',   ['AnnouncementController', 'store']);
        $router->post('/admin/announcements/update',  ['AnnouncementController', 'update']);
        $router->post('/admin/announcements/delete',  ['AnnouncementController', 'destroy']);
        $router->get('/admin/reports',            ['ReportController', 'index']);
        $router->post('/admin/reports/generate',  ['ReportController', 'generate']);
        $router->get('/admin/activity-logs',      ['ActivityLogController', 'index']);
    } elseif ($role === 'teacher') {
        $router->get('/teacher',                    ['DashboardController', 'teacherDashboard']);
        $router->get('/teacher/dashboard',          ['DashboardController', 'teacherDashboard']);
        $router->get('/teacher/classes',            ['ScheduleController', 'teacherClasses']);
        $router->get('/teacher/grades',             ['GradeController', 'teacherGrades']);
        $router->post('/teacher/grades/store',      ['GradeController', 'store']);
        $router->post('/teacher/grades/update',     ['GradeController', 'update']);
        $router->get('/teacher/announcements',      ['AnnouncementController', 'teacherAnnouncements']);
    } elseif ($role === 'student') {
        $router->get('/student',                    ['DashboardController', 'studentDashboard']);
        $router->get('/student/dashboard',          ['DashboardController', 'studentDashboard']);
        $router->get('/student/schedule',           ['ScheduleController', 'studentSchedule']);
        $router->get('/student/grades',             ['GradeController', 'studentGrades']);
        $router->get('/student/announcements',      ['AnnouncementController', 'studentAnnouncements']);
    }

    return $router;
}