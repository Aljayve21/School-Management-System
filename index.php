<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/core/Response.php';
require_once __DIR__ . '/core/Session.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Validator.php';
require_once __DIR__ . '/core/Middleware.php';

require_once __DIR__ . '/interfaces/IRepository.php';
require_once __DIR__ . '/interfaces/IAuthenticatable.php';
require_once __DIR__ . '/interfaces/IActivityLogger.php';
require_once __DIR__ . '/interfaces/IGradeCalculator.php';
require_once __DIR__ . '/interfaces/IReportGenerator.php';

require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Student.php';
require_once __DIR__ . '/models/Teacher.php';
require_once __DIR__ . '/models/Subject.php';
require_once __DIR__ . '/models/Schedule.php';
require_once __DIR__ . '/models/Grade.php';
require_once __DIR__ . '/models/Announcement.php';
require_once __DIR__ . '/models/ActivityLog.php';

require_once __DIR__ . '/repositories/UserRepository.php';
require_once __DIR__ . '/repositories/StudentRepository.php';
require_once __DIR__ . '/repositories/TeacherRepository.php';
require_once __DIR__ . '/repositories/SubjectRepository.php';
require_once __DIR__ . '/repositories/ScheduleRepository.php';
require_once __DIR__ . '/repositories/GradeRepository.php';
require_once __DIR__ . '/repositories/AnnouncementRepository.php';
require_once __DIR__ . '/repositories/ActivityLogRepository.php';

require_once __DIR__ . '/services/AuthService.php';
require_once __DIR__ . '/services/StudentService.php';
require_once __DIR__ . '/services/TeacherService.php';
require_once __DIR__ . '/services/SubjectService.php';
require_once __DIR__ . '/services/ScheduleService.php';
require_once __DIR__ . '/services/GradeService.php';
require_once __DIR__ . '/services/AnnouncementService.php';
require_once __DIR__ . '/services/ReportService.php';
require_once __DIR__ . '/services/ActivityLogService.php';

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/StudentController.php';
require_once __DIR__ . '/controllers/TeacherController.php';
require_once __DIR__ . '/controllers/SubjectController.php';
require_once __DIR__ . '/controllers/ScheduleController.php';
require_once __DIR__ . '/controllers/GradeController.php';
require_once __DIR__ . '/controllers/AnnouncementController.php';
require_once __DIR__ . '/controllers/ReportController.php';
require_once __DIR__ . '/controllers/ActivityLogController.php';

require_once __DIR__ . '/middleware/AuthMiddleware.php';
require_once __DIR__ . '/middleware/RoleMiddleware.php';
require_once __DIR__ . '/middleware/ActivityLogMiddleware.php';

require_once __DIR__ . '/helpers/url.php';
require_once __DIR__ . '/helpers/format.php';
require_once __DIR__ . '/helpers/auth.php';

require_once __DIR__ . '/config/routes.php';

$session = new Session();
$role    = $session->userRole() ?? '';
$router  = getRouter($role);
$router->dispatch(new Request());