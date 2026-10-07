# Campus Club Management System

A PHP and MySQL application for managing campus clubs, memberships, events, announcements, and student accounts. It is designed to run locally with XAMPP.

## Requirements

- Windows with XAMPP (Apache, MySQL/MariaDB, and PHP)
- PHP PDO MySQL extension enabled

## Setup

1. Place this folder at `C:\xampp\htdocs\CampusClubManagement`.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Open phpMyAdmin at `http://localhost/phpmyadmin/` and import `database/campus_club.sql`.
4. If your MySQL credentials differ from the XAMPP defaults, update `config/db.php`.
5. Visit `http://localhost/CampusClubManagement/`.

The SQL script creates and seeds the `campus_club` database.

## Demo accounts

| Role | Email | Password |
| --- | --- | --- |
| Admin | `admin@campus.com` | `admin123` |
| Student | `student@campus.com` | `student123` |

These accounts are for local development only. Change or remove the demo credentials before deploying this application or exposing it to a network.

## Main areas

- Public landing page, login, and student registration
- Admin dashboards and management of students, clubs, events, memberships, registrations, and announcements
- Student dashboards, club memberships, event registration, announcements, and profile management
- Database schema and sample data in `database/campus_club.sql`
