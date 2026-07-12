CREATE DATABASE IF NOT EXISTS school_ms DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE school_ms;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','teacher','student') NOT NULL,
    status ENUM('active','inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE teachers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    teacher_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    department VARCHAR(100),
    specialization VARCHAR(200),
    phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNIQUE NOT NULL,
    student_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    grade_level INT NOT NULL,
    section VARCHAR(50),
    date_of_birth DATE,
    gender ENUM('male','female','other'),
    guardian_name VARCHAR(200),
    guardian_phone VARCHAR(20),
    address TEXT,
    profile_image VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_code VARCHAR(20) UNIQUE NOT NULL,
    subject_name VARCHAR(200) NOT NULL,
    description TEXT,
    grade_level INT NOT NULL,
    credits INT DEFAULT 1,
    status ENUM('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB;

CREATE TABLE teacher_subjects (
    id INT PRIMARY KEY AUTO_INCREMENT,
    teacher_id INT NOT NULL,
    subject_id INT NOT NULL,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    UNIQUE KEY unique_assignment (teacher_id, subject_id)
) ENGINE=InnoDB;

CREATE TABLE schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    subject_id INT NOT NULL,
    teacher_id INT NOT NULL,
    grade_level INT NOT NULL,
    section VARCHAR(50) NOT NULL,
    day_of_week ENUM('Monday','Tuesday','Wednesday','Thursday','Friday') NOT NULL,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    room VARCHAR(50),
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE grades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    subject_id INT NOT NULL,
    teacher_id INT,
    semester INT NOT NULL,
    academic_year VARCHAR(20) NOT NULL,
    written_work DECIMAL(5,2) DEFAULT 0,
    performance_task DECIMAL(5,2) DEFAULT 0,
    quarterly_exam DECIMAL(5,2) DEFAULT 0,
    quarterly_grade DECIMAL(5,2) DEFAULT 0,
    remarks VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES teachers(id) ON DELETE SET NULL,
    UNIQUE KEY unique_grade (student_id, subject_id, semester, academic_year)
) ENGINE=InnoDB;

CREATE TABLE announcements (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    target_role ENUM('all','admin','teacher','student') DEFAULT 'all',
    target_grade_level INT NULL,
    priority ENUM('low','medium','high') DEFAULT 'medium',
    posted_by INT NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    entity_type VARCHAR(50),
    entity_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ============================================
-- SEED DATA
-- ============================================

-- All users password: password
-- Admin user
INSERT INTO users (email, password, role, status) VALUES
('admin@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'active');

-- Teacher users
INSERT INTO users (email, password, role, status) VALUES
('john.doe@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active'),
('jane.smith@school.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'teacher', 'active');

-- Student users
INSERT INTO users (email, password, role, status) VALUES
('alice.johnson@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('bob.williams@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('charlie.brown@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('diana.prince@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('edward.norton@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active'),
('fiona.apple@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 'active');

-- Teachers
INSERT INTO teachers (user_id, teacher_id, first_name, last_name, department, specialization, phone) VALUES
(2, 'T001', 'John', 'Doe', 'Science', 'Physics', '09171234567'),
(3, 'T002', 'Jane', 'Smith', 'Mathematics', 'Algebra', '09181234567');

-- Students
INSERT INTO students (user_id, student_id, first_name, last_name, grade_level, section, gender, guardian_name, guardian_phone) VALUES
(4, 'S20260001', 'Alice', 'Johnson', 7, 'A', 'female', 'Robert Johnson', '09191234567'),
(5, 'S20260002', 'Bob', 'Williams', 7, 'A', 'male', 'Sarah Williams', '09201234567'),
(6, 'S20260003', 'Charlie', 'Brown', 8, 'B', 'male', 'Linda Brown', '09211234567'),
(7, 'S20260004', 'Diana', 'Prince', 8, 'B', 'female', 'Steve Prince', '09221234567'),
(8, 'S20260005', 'Edward', 'Norton', 7, 'A', 'male', 'Grace Norton', '09231234567'),
(9, 'S20260006', 'Fiona', 'Apple', 9, 'C', 'female', 'Mark Apple', '09241234567');

-- Subjects
INSERT INTO subjects (subject_code, subject_name, description, grade_level, credits, status) VALUES
('MATH7', 'Mathematics 7', 'Basic Mathematics for Grade 7', 7, 1, 'active'),
('ENG7', 'English 7', 'English Communication for Grade 7', 7, 1, 'active'),
('SCI7', 'Science 7', 'General Science for Grade 7', 7, 1, 'active'),
('FIL7', 'Filipino 7', 'Filipino Wika for Grade 7', 7, 1, 'active'),
('MATH8', 'Mathematics 8', 'Intermediate Mathematics for Grade 8', 8, 1, 'active'),
('ENG8', 'English 8', 'English Communication for Grade 8', 8, 1, 'active'),
('SCI8', 'Science 8', 'General Science for Grade 8', 8, 1, 'active'),
('MATH9', 'Mathematics 9', 'Algebra for Grade 9', 9, 1, 'active');

-- Teacher-Subject Assignments
INSERT INTO teacher_subjects (teacher_id, subject_id) VALUES
(1, 1), (1, 3), (1, 5), (1, 7),
(2, 2), (2, 4), (2, 6), (2, 8);

-- Schedules
INSERT INTO schedules (subject_id, teacher_id, grade_level, section, day_of_week, time_start, time_end, room) VALUES
(1, 1, 7, 'A', 'Monday',    '08:00', '09:00', 'Room 101'),
(2, 2, 7, 'A', 'Monday',    '09:00', '10:00', 'Room 101'),
(3, 1, 7, 'A', 'Tuesday',   '08:00', '09:00', 'Room 101'),
(4, 2, 7, 'A', 'Tuesday',   '09:00', '10:00', 'Room 101'),
(1, 1, 7, 'A', 'Wednesday', '08:00', '09:00', 'Room 101'),
(5, 1, 8, 'B', 'Monday',    '10:00', '11:00', 'Room 102'),
(6, 2, 8, 'B', 'Monday',    '11:00', '12:00', 'Room 102'),
(7, 1, 8, 'B', 'Tuesday',   '10:00', '11:00', 'Room 102'),
(8, 2, 9, 'C', 'Wednesday', '10:00', '11:00', 'Room 103');

-- Grades
INSERT INTO grades (student_id, subject_id, teacher_id, semester, academic_year, written_work, performance_task, quarterly_exam, quarterly_grade, remarks) VALUES
(1, 1, 1, 1, '2026', 85.00, 90.00, 80.00, 87.00, 'Satisfactory'),
(1, 2, 2, 1, '2026', 90.00, 85.00, 88.00, 87.30, 'Satisfactory'),
(1, 3, 1, 1, '2026', 78.00, 82.00, 75.00, 79.60, 'Fairly Satisfactory'),
(2, 1, 1, 1, '2026', 92.00, 88.00, 90.00, 89.40, 'Satisfactory'),
(2, 2, 2, 1, '2026', 88.00, 92.00, 85.00, 89.60, 'Satisfactory'),
(3, 5, 1, 1, '2026', 80.00, 78.00, 76.00, 78.00, 'Fairly Satisfactory'),
(3, 6, 2, 1, '2026', 85.00, 80.00, 82.00, 81.60, 'Satisfactory'),
(4, 5, 1, 1, '2026', 95.00, 92.00, 90.00, 92.40, 'Outstanding'),
(4, 6, 2, 1, '2026', 90.00, 88.00, 85.00, 87.80, 'Satisfactory');

-- Announcements
INSERT INTO announcements (title, content, target_role, priority, posted_by, is_active) VALUES
('Welcome Back to School!', 'Welcome to the new school year 2026-2027. We look forward to a productive and exciting year ahead.', 'all', 'high', 1, 1),
('Midterm Examination Schedule', 'Midterm examinations will be held from October 15-22, 2026. Please review your respective schedules.', 'all', 'high', 1, 1),
('Science Fair 2026', 'The annual Science Fair will be held on November 15, 2026. All Grade 7-9 students are encouraged to participate.', 'student', 'medium', 1, 1),
('Faculty Meeting', 'There will be a mandatory faculty meeting this Friday at 3:00 PM in the Conference Room.', 'teacher', 'medium', 1, 1);

-- Activity Logs
INSERT INTO activity_logs (user_id, action, entity_type, entity_id, ip_address) VALUES
(1, 'System initialized', NULL, NULL, '127.0.0.1'),
(1, 'Created announcement', 'announcement', 1, '127.0.0.1'),
(1, 'Created announcement', 'announcement', 2, '127.0.0.1');