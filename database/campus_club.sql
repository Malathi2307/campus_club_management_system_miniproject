CREATE DATABASE IF NOT EXISTS `campus_club`;
USE `campus_club`;

DROP TABLE IF EXISTS `event_registrations`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `memberships`;
DROP TABLE IF EXISTS `clubs`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `student_id` VARCHAR(100) NOT NULL UNIQUE,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `phone` VARCHAR(20) DEFAULT NULL,
  `department` VARCHAR(100) NOT NULL,
  `year` VARCHAR(20) NOT NULL,
  `role` ENUM('admin','student') NOT NULL DEFAULT 'student',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_user_email` (`email`),
  INDEX `idx_user_student_id` (`student_id`)
);

CREATE TABLE `clubs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `description` TEXT NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `faculty_coordinator` VARCHAR(150) NOT NULL,
  `contact_email` VARCHAR(150) NOT NULL,
  `meeting_location` VARCHAR(180) NOT NULL,
  `meeting_schedule` VARCHAR(120) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_club_category` (`category`),
  INDEX `idx_club_name` (`name`)
);

CREATE TABLE `memberships` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `club_id` INT NOT NULL,
  `status` ENUM('active','pending','rejected') NOT NULL DEFAULT 'active',
  `joined_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `uq_membership` (`user_id`, `club_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`club_id`) REFERENCES `clubs`(`id`) ON DELETE CASCADE
);

CREATE TABLE `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `club_id` INT NOT NULL,
  `title` VARCHAR(180) NOT NULL,
  `description` TEXT NOT NULL,
  `event_date` DATE NOT NULL,
  `event_time` TIME NOT NULL,
  `venue` VARCHAR(180) NOT NULL,
  `max_participants` INT NOT NULL,
  `registration_deadline` DATE NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('open','closed') NOT NULL DEFAULT 'open',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`club_id`) REFERENCES `clubs`(`id`) ON DELETE CASCADE,
  INDEX `idx_event_date` (`event_date`),
  INDEX `idx_event_title` (`title`)
);

CREATE TABLE `event_registrations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `event_id` INT NOT NULL,
  `registered_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('registered','cancelled') NOT NULL DEFAULT 'registered',
  UNIQUE KEY `uq_event_registration` (`user_id`, `event_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
);

CREATE TABLE `announcements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(180) NOT NULL,
  `message` TEXT NOT NULL,
  `posted_by` VARCHAR(120) NOT NULL,
  `status` ENUM('published','draft') NOT NULL DEFAULT 'published',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_announcement_status` (`status`)
);

INSERT INTO users (student_id, name, email, password, phone, department, year, role, status, created_at) VALUES
('ADMIN001', 'System Administrator', 'admin@campus.com', '$2y$10$5HsSDbtUXXh/SMi8YlGbv.F5FtUcxqPtrKuceZxQXl.QApumVreua', '9876543210', 'Administration', '1', 'admin', 'active', NOW()),
('CS2024001', 'Aisha Patel', 'student@campus.com', '$2y$10$G.ke4Y3u17cbCs8da5APIOSCnix4JRVUjl6RqiCLoh221LG24/w.W', '9988776655', 'Computer Science', '2', 'student', 'active', NOW()),
('CS2024002', 'Rahul Sharma', 'rahul@campus.com', '$2y$10$G.ke4Y3u17cbCs8da5APIOSCnix4JRVUjl6RqiCLoh221LG24/w.W', '9123456780', 'Computer Science', '2', 'student', 'active', NOW()),
('EC2024003', 'Priya Nair', 'priya@campus.com', '$2y$10$G.ke4Y3u17cbCs8da5APIOSCnix4JRVUjl6RqiCLoh221LG24/w.W', '9807654321', 'Electronics', '3', 'student', 'active', NOW()),
('ME2024004', 'Arjun Das', 'arjun@campus.com', '$2y$10$G.ke4Y3u17cbCs8da5APIOSCnix4JRVUjl6RqiCLoh221LG24/w.W', '9543216789', 'Mechanical', '1', 'student', 'active', NOW());

INSERT INTO clubs (name, description, category, faculty_coordinator, contact_email, meeting_location, meeting_schedule, image, status, created_at) VALUES
('Coding Club', 'Hands-on programming, hackathons and coding competitions for all skill levels.', 'Technology', 'Dr. Meera Joshi', 'codingclub@campus.edu', 'Innovation Lab', 'Every Wednesday 5:00 PM', NULL, 'active', NOW()),
('Robotics Club', 'Build robots, participate in competitions and learn automation techniques.', 'STEM', 'Prof. Sudhir Rao', 'robotics@campus.edu', 'Robotics Lab', 'Every Thursday 4:30 PM', NULL, 'active', NOW()),
('Literary Club', 'Read, write and discuss literature, poems and public speaking.', 'Arts', 'Ms. Neha Singh', 'literary@campus.edu', 'Library Hall', 'Every Tuesday 3:00 PM', NULL, 'active', NOW()),
('Sports Club', 'Promote fitness, teamwork and campus tournaments across major sports.', 'Sports', 'Mr. Vinod Kumar', 'sports@campus.edu', 'Sports Ground', 'Every Saturday 8:00 AM', NULL, 'active', NOW()),
('Cultural Club', 'Organize cultural events, dance, music and ensemble performances.', 'Culture', 'Dr. Kavya Menon', 'culture@campus.edu', 'Auditorium', 'Every Friday 6:00 PM', NULL, 'active', NOW());

INSERT INTO memberships (user_id, club_id, status, joined_at) VALUES
(2, 1, 'active', NOW()),
(2, 3, 'active', NOW()),
(3, 2, 'active', NOW()),
(4, 4, 'active', NOW()),
(5, 5, 'active', NOW());

INSERT INTO events (club_id, title, description, event_date, event_time, venue, max_participants, registration_deadline, image, status, created_at) VALUES
(1, 'Hackathon 2026', 'A 12-hour coding challenge to build innovative solutions.', '2026-11-15', '09:00:00', 'Computer Lab 2', 80, '2026-11-10', NULL, 'open', NOW()),
(2, 'Robotics Expo', 'Showcase robotic projects and explore automation ideas.', '2026-11-22', '11:00:00', 'Innovation Center', 60, '2026-11-18', NULL, 'open', NOW()),
(3, 'Poetry Night', 'Open mic and poetry reading event with student performances.', '2026-12-01', '18:00:00', 'Auditorium', 75, '2026-11-25', NULL, 'open', NOW()),
(4, 'Inter-College Sports Fest', 'A competitive sports event bringing students together.', '2026-12-05', '08:00:00', 'Main Sports Ground', 120, '2026-11-30', NULL, 'open', NOW()),
(5, 'Cultural Parade', 'A vibrant cultural festival with music, dance and theatre.', '2026-12-10', '17:30:00', 'Open Air Theatre', 100, '2026-12-05', NULL, 'open', NOW());

INSERT INTO event_registrations (user_id, event_id, registered_at, status) VALUES
(2, 1, NOW(), 'registered'),
(3, 2, NOW(), 'registered'),
(4, 4, NOW(), 'registered'),
(5, 5, NOW(), 'registered');

INSERT INTO announcements (title, message, posted_by, status, created_at) VALUES
('Orientation Week', 'All new students are welcome to join club orientation events this week.', 'System Administrator', 'published', NOW()),
('Hackathon Registration Open', 'Students may register for the upcoming campus hackathon before the deadline.', 'System Administrator', 'published', NOW()),
('Sports Fest Update', 'Practice sessions for the sports festival start next Monday.', 'System Administrator', 'published', NOW()),
('Literary Society Meet', 'Join the literary club for a poetry and storytelling session on Friday.', 'System Administrator', 'published', NOW()),
('Cultural Night Details', 'The cultural club will hold auditions for the upcoming parade.', 'System Administrator', 'published', NOW());
